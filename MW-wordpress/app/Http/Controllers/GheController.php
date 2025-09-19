<?php

namespace App\Http\Controllers;

use App\Models\Ghe;
use App\Models\PhongChieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GheController extends BaseCrudController
{
    protected $model = Ghe::class;

    public function index()
    {
        $phongChieus = PhongChieu::orderBy('MaPhong')->get();
        $ghes = Ghe::with('phongChieu')->orderBy('MaPhong')->orderBy('SoGhe')->get();

        $editingGhe = null;
        if (request()->has('edit_ma_phong') && request()->has('edit_so_ghe')) {
            $editingGhe = Ghe::where('MaPhong', request()->query('edit_ma_phong'))
                            ->where('SoGhe', request()->query('edit_so_ghe'))
                            ->first();
        }

        return view('AdminGhe', compact('phongChieus', 'ghes', 'editingGhe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaPhong' => 'required|integer|exists:PhongChieu,MaPhong',
            'mode' => 'required|in:single,bulk',
        ]);

        $maPhong = $request->input('MaPhong');
        $mode = $request->input('mode');
        $phong = PhongChieu::find($maPhong);

        return DB::transaction(function() use ($request, $maPhong, $mode, $phong) {
            // Kiểm tra số ghế hiện tại
            $soGheHienTai = Ghe::where('MaPhong', $maPhong)->count();
            
            if ($mode === 'single') {
                // VALIDATION MỚI: Kiểm tra không vượt quá số lượng ghế cho phép
                if ($soGheHienTai >= $phong->SoLuongGhe) {
                    return back()->withErrors(['MaPhong' => 'Phòng đã đạt số lượng ghế tối đa ('.$phong->SoLuongGhe.')'])->withInput();
                }

                $request->validate([
                    'SoGhe' => 'required|string|max:10',
                ]);

                $soGhe = strtoupper(trim($request->input('SoGhe')));

                if (!$soGhe) {
                    return back()->withErrors(['SoGhe' => 'Vui lòng nhập mã ghế'])->withInput();
                }

                if (Ghe::where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->exists()) {
                    return back()->withErrors(['SoGhe' => "Ghế $soGhe đã tồn tại"])->withInput();
                }

                Ghe::create(['MaPhong' => $maPhong, 'SoGhe' => $soGhe]);

                return redirect()->route('ghe.index')->with('success', "Thêm ghế $soGhe thành công");
            }

            // bulk mode
            // VALIDATION MỚI: Kiểm tra số lượng ghế muốn thêm
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'seats_per_row' => 'nullable|integer|min:1',
            ]);

            $quantity = (int) $request->input('quantity', 0);
            $seatsPerRow = (int) $request->input('seats_per_row', 10);
            if ($seatsPerRow <= 0) $seatsPerRow = 10;
            
            // Kiểm tra xem có đủ chỗ để thêm số ghế yêu cầu không
            $soGheConLai = $phong->SoLuongGhe - $soGheHienTai;
            if ($quantity > $soGheConLai) {
                return back()->withErrors(['quantity' => 'Chỉ còn '.$soGheConLai.' ghế trống trong phòng này'])->withInput();
            }

            $created = 0;
            $skipped = 0;

            $getRowLabel = function($index) {
                $label = '';
                $n = $index;
                while ($n >= 0) {
                    $label = chr(65 + ($n % 26)) . $label;
                    $n = intval($n / 26) - 1;
                }
                return $label;
            };

            $rowIndex = 0;
            $seatNum = 1;

            while ($created < $quantity && $created < $soGheConLai) {
                $rowLabel = $getRowLabel($rowIndex);
                $seatCode = $rowLabel . $seatNum;

                if (Ghe::where('MaPhong', $maPhong)->where('SoGhe', $seatCode)->exists()) {
                    $skipped++;
                } else {
                    Ghe::create(['MaPhong' => $maPhong, 'SoGhe' => $seatCode]);
                    $created++;
                }

                $seatNum++;
                if ($seatNum > $seatsPerRow) {
                    $seatNum = 1;
                    $rowIndex++;
                }

                if ($rowIndex > 10000) break;
            }

            $msg = "Thêm hàng loạt hoàn tất. Tạo: $created; Bỏ qua (trùng): $skipped.";
            return redirect()->route('ghe.index')->with('success', $msg);
        });
    }

    public function update(Request $request, $maPhongOrId, $soGhe = null)
    {
        if ($soGhe === null) {
            // Handle single parameter case with delimiter
            $params = explode('|', $maPhongOrId);
            if (count($params) !== 2) {
                return back()->withErrors(['error' => 'Định dạng tham số không hợp lệ'])->withInput();
            }
            list($maPhong, $soGhe) = $params;
        } else {
            $maPhong = $maPhongOrId;
        }

        $request->validate([
            'SoGhe' => 'required|string|max:10',
        ]);

        $maPhong = trim($maPhong);
        $soGhe = trim($soGhe);
        $newSoGhe = strtoupper(trim($request->input('SoGhe')));

        if (!$maPhong || !$soGhe) {
            return back()->withErrors(['error' => 'Thiếu khóa MaPhong hoặc SoGhe'])->withInput();
        }

        if ($newSoGhe === $soGhe) {
            return redirect()->route('ghe.index')->with('success', 'Không thay đổi mã ghế');
        }

        $exists = Ghe::where('MaPhong', $maPhong)
                     ->where('SoGhe', $newSoGhe)
                     ->exists();

        if ($exists) {
            return back()->withErrors(['SoGhe' => 'Mã ghế đã tồn tại'])->withInput();
        }

        // Use query builder instead of Eloquent for update
        $updated = DB::table('Ghe')
            ->where('MaPhong', $maPhong)
            ->where('SoGhe', $soGhe)
            ->update(['SoGhe' => $newSoGhe]);

        if (!$updated) {
            return back()->withErrors(['error' => 'Ghế không tồn tại'])->withInput();
        }

        return redirect()->route('ghe.index')->with('success', 'Cập nhật ghế thành công');
    }

    public function destroy($maPhongOrId, $soGhe = null)
    {
        if ($soGhe === null) {
            // Handle single parameter case with delimiter
            $params = explode('|', $maPhongOrId);
            if (count($params) !== 2) {
                return redirect()->route('ghe.index')->withErrors(['error' => 'Định dạng tham số không hợp lệ']);
            }
            list($maPhong, $soGhe) = $params;
        } else {
            $maPhong = $maPhongOrId;
        }

        $maPhong = trim($maPhong);
        $soGhe = trim($soGhe);

        // Use query builder instead of Eloquent for delete
        $deleted = DB::table('Ghe')
            ->where('MaPhong', $maPhong)
            ->where('SoGhe', $soGhe)
            ->delete();

        if (!$deleted) {
            return redirect()->route('ghe.index')->withErrors(['error' => 'Ghế không tồn tại']);
        }

        return redirect()->route('ghe.index')->with('success', 'Xóa ghế thành công');
    }

    public function edit($maPhong, $soGhe)
    {
        return redirect()->route('ghe.index', [
            'edit_ma_phong' => $maPhong,
            'edit_so_ghe' => $soGhe,
        ]);
    }
}