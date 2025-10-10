<?php

namespace App\Http\Controllers;

use App\Models\Ghe;
use App\Models\PhongChieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GheController extends Controller
{
    /**
     * Hiển thị trang quản lý ghế (list + forms).
     */
    public function index()
    {
        // Lấy danh sách ghế và phòng chiếu để hiển thị
        $ghes = Ghe::with('phongChieu')->orderBy('MaPhong')->orderBy('SoGhe')->get();
        $phongChieus = PhongChieu::orderBy('TenPhong')->get();

        return view('AdminGhe', [
            'ghes' => $ghes,
            'phongChieus' => $phongChieus,
            'editingGhe' => null
        ]);
    }

    /**
     * Form edit
     */
    public function edit($maPhong, $soGhe)
    {
        $ghe = DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->first();
        if (!$ghe) {
            return redirect()->route('ghe.index')->withErrors(['error' => 'Ghế không tồn tại.']);
        }

        $ghes = Ghe::with('phongChieu')->orderBy('MaPhong')->orderBy('SoGhe')->get();
        $phongChieus = PhongChieu::orderBy('TenPhong')->get();

        return view('AdminGhe', [
            'ghes' => $ghes,
            'phongChieus' => $phongChieus,
            'editingGhe' => $ghe
        ]);
    }

    /**
     * Kiểm tra xem phòng đã đủ ghế chưa
     */
    private function kiemTraPhongDaDuGhe($maPhong)
    {
        $phong = PhongChieu::find($maPhong);
        if (!$phong) {
            return ['error' => 'Phòng không tồn tại.'];
        }

        $soGheHienCo = DB::table('Ghe')->where('MaPhong', $maPhong)->count();
        
        if ($soGheHienCo >= $phong->SoLuongGhe) {
            return ['error' => "Phòng này đã đủ số lượng ghế ($soGheHienCo/$phong->SoLuongGhe). Không thể thêm ghế mới."];
        }

        return ['success' => true, 'soGheHienCo' => $soGheHienCo, 'soLuongGheToiDa' => $phong->SoLuongGhe];
    }

    /**
     * Kiểm tra ghế đã tồn tại trong phòng chưa
     */
    private function kiemTraGheTonTai($maPhong, $soGhe)
    {
        $exists = DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->exists();
        return $exists;
    }

    /**
     * Thêm ghế (single hoặc bulk) - ĐÃ SỬA LỖI TIMESTAMPS VÀ THÊM KIỂM TRA
     */
    public function store(Request $request)
    {
        $mode = $request->input('mode', 'single');

        if ($mode === 'bulk') {
            $request->validate([
                'MaPhong' => 'required',
                'quantity' => 'required|integer|min:1|max:500',
                'seats_per_row' => 'nullable|integer|min:1|max:20',
            ]);

            $maPhong = $request->input('MaPhong');
            $quantity = (int)$request->input('quantity');
            $perRow = (int)$request->input('seats_per_row', 10);

            // KIỂM TRA: Phòng đã đủ ghế chưa
            $kiemTraPhong = $this->kiemTraPhongDaDuGhe($maPhong);
            if (isset($kiemTraPhong['error'])) {
                return back()->withErrors(['MaPhong' => $kiemTraPhong['error']])->withInput();
            }

            $soGheConThieu = $kiemTraPhong['soLuongGheToiDa'] - $kiemTraPhong['soGheHienCo'];
            if ($quantity > $soGheConThieu) {
                return back()->withErrors(['quantity' => "Số lượng ghế yêu cầu ($quantity) vượt quá số ghế còn thiếu ($soGheConThieu)."])->withInput();
            }

            // Lấy danh sách ghế hiện tại
            $existing = DB::table('Ghe')
                ->where('MaPhong', $maPhong)
                ->pluck('SoGhe')
                ->toArray();

            $created = 0;
            $rowChar = 'A';
            $colIndex = 1;
            $maxAttempts = $quantity * 2;
            $attempts = 0;

            DB::beginTransaction();
            try {
                while ($created < $quantity && $attempts < $maxAttempts) {
                    $soGhe = $rowChar . str_pad($colIndex, 2, '0', STR_PAD_LEFT);
                    
                    if (!in_array($soGhe, $existing)) {
                        // SỬA: Chỉ insert MaPhong và SoGhe, không có timestamps
                        DB::table('Ghe')->insert([
                            'MaPhong' => $maPhong,
                            'SoGhe' => $soGhe
                        ]);
                        $existing[] = $soGhe;
                        $created++;
                    }
                    
                    $colIndex++;
                    if ($colIndex > $perRow) {
                        $rowChar++;
                        $colIndex = 1;
                        if (ord($rowChar) > ord('Z')) break;
                    }
                    $attempts++;
                }

                DB::commit();
                
                if ($created < $quantity) {
                    return redirect()->route('ghe.index')
                        ->with('warning', "Chỉ thêm được $created/$quantity ghế. Có thể đã hết số ghế có thể tạo.");
                }
                
                return redirect()->route('ghe.index')
                    ->with('success', "Thêm hàng loạt $created ghế thành công.");
                    
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Bulk seat creation error: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Lỗi khi thêm hàng loạt: ' . $e->getMessage()]);
            }
        }

        // --- Single add - ĐÃ SỬA LỖI TIMESTAMPS VÀ THÊM KIỂM TRA ---
        $request->validate([
            'MaPhong' => 'required',
            'SoGhe' => ['required', 'string', 'max:5', 'regex:/^[A-Z][A-Za-z0-9]{0,4}$/'],
        ], [
            'SoGhe.regex' => 'Mã ghế phải bắt đầu bằng chữ in hoa (A-Z) và chỉ chứa chữ/số, tối đa 5 ký tự.',
            'SoGhe.max' => 'Mã ghế tối đa 5 ký tự.',
        ]);

        $maPhong = $request->input('MaPhong');
        $soGhe = $request->input('SoGhe');

        // KIỂM TRA: Phòng đã đủ ghế chưa
        $kiemTraPhong = $this->kiemTraPhongDaDuGhe($maPhong);
        if (isset($kiemTraPhong['error'])) {
            return back()->withErrors(['MaPhong' => $kiemTraPhong['error']])->withInput();
        }

        // KIỂM TRA: Ghế đã tồn tại trong phòng chưa
        if ($this->kiemTraGheTonTai($maPhong, $soGhe)) {
            return back()->withErrors(['SoGhe' => "Ghế $soGhe đã tồn tại trong phòng $maPhong."])->withInput();
        }

        try {
            // SỬA: Chỉ insert MaPhong và SoGhe, không có timestamps
            DB::table('Ghe')->insert([
                'MaPhong' => $maPhong,
                'SoGhe' => $soGhe
            ]);
            return redirect()->route('ghe.index')->with('success', 'Thêm ghế thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi khi thêm ghế: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Cập nhật ghế - ĐÃ SỬA LỖI TIMESTAMPS VÀ THÊM KIỂM TRA
     */
    public function update(Request $request, $maPhong, $soGhe)
    {
        $request->validate([
            'SoGhe' => ['required', 'string', 'max:5', 'regex:/^[A-Z][A-Za-z0-9]{0,4}$/'],
        ], [
            'SoGhe.regex' => 'Mã ghế phải bắt đầu bằng chữ in hoa (A-Z) và chỉ chứa chữ/số, tối đa 5 ký tự.',
            'SoGhe.max' => 'Mã ghế tối đa 5 ký tự.',
        ]);

        $newSoGhe = $request->input('SoGhe');

        // nếu không đổi SoGhe
        if ($newSoGhe === $soGhe) {
            // Không cần update gì nếu chỉ có MaPhong và SoGhe
            return redirect()->route('ghe.index')->with('success', 'Cập nhật ghế thành công.');
        }

        // KIỂM TRA: Ghế mới đã tồn tại trong phòng chưa
        if ($this->kiemTraGheTonTai($maPhong, $newSoGhe)) {
            return back()->withErrors(['SoGhe' => "Ghế $newSoGhe đã tồn tại trong phòng $maPhong. Vui lòng chọn số ghế khác."])->withInput();
        }

        DB::beginTransaction();
        try {
            // lấy bản ghi cũ
            $gheOld = DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->first();
            if (!$gheOld) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Ghế cũ không tìm thấy.']);
            }

            $dbName = DB::getDatabaseName();
            $autoCols = collect(DB::select(
                "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND EXTRA LIKE '%auto_increment%'",
                [$dbName, 'Ghe']
            ))->pluck('COLUMN_NAME')->toArray();

            $columns = Schema::getColumnListing('Ghe');

            // chuẩn bị row mới - LOẠI BỎ TIMESTAMPS
            $newRow = [];
            foreach ($columns as $col) {
                if (in_array($col, $autoCols)) continue;
                // Bỏ qua các trường timestamps
                if (in_array($col, ['created_at', 'updated_at'])) continue;
                
                if ($col === 'SoGhe') {
                    $newRow[$col] = $newSoGhe;
                } else {
                    $newRow[$col] = $gheOld->{$col} ?? null;
                }
            }

            // insert ghế mới
            DB::table('Ghe')->insert($newRow);

            // tìm tất cả FK tham chiếu tới Ghe
            $fks = DB::select(
                "SELECT TABLE_NAME, COLUMN_NAME
                 FROM information_schema.KEY_COLUMN_USAGE
                 WHERE REFERENCED_TABLE_NAME = ? AND REFERENCED_TABLE_SCHEMA = ?",
                ['Ghe', $dbName]
            );

            $childTables = [];
            foreach ($fks as $fk) {
                $childTables[$fk->TABLE_NAME][] = $fk->COLUMN_NAME;
            }

            // update tất cả bảng con (nếu có column SoGhe)
            foreach ($childTables as $table => $cols) {
                if (in_array('SoGhe', $cols)) {
                    DB::table($table)
                        ->where('MaPhong', $maPhong)
                        ->where('SoGhe', $soGhe)
                        ->update(['SoGhe' => $newSoGhe]);
                } else {
                    // Nếu bảng con dùng tên cột khác (ví dụ 'so_ghe'), bạn cần map thủ công ở đây
                    if (in_array('so_ghe', $cols)) {
                        DB::table($table)
                            ->where('MaPhong', $maPhong)
                            ->where('so_ghe', $soGhe)
                            ->update(['so_ghe' => $newSoGhe]);
                    }
                }
            }

            // xóa ghế cũ
            DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->delete();

            DB::commit();
            return redirect()->route('ghe.index')->with('success', 'Đổi mã ghế thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ghe update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi khi cập nhật ghế: ' . $e->getMessage()]);
        }
    }

    /**
     * Xóa ghế
     */
    public function destroy($maPhong, $soGhe)
    {
        try {
            // KIỂM TRA: Ghế có tồn tại không
            $ghe = DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->first();
            if (!$ghe) {
                return back()->withErrors(['error' => 'Ghế không tồn tại.']);
            }

            DB::table('Ghe')->where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->delete();
            return redirect()->route('ghe.index')->with('success', 'Xóa ghế thành công.');
        } catch (\Exception $e) {
            \Log::error('Ghe delete error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi khi xóa ghế: ' . $e->getMessage()]);
        }
    }
}