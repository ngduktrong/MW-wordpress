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
        // eager load phongChieu to avoid N+1 when blade accesses $ghe->phongChieu
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
            'SoGhe' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer|min:1',
            'seats_per_row' => 'nullable|integer|min:1|max:99',
        ]);

        $maPhong = $request->input('MaPhong');
        $mode = $request->input('mode');
        $phong = PhongChieu::findOrFail($maPhong);
        $maxSeats = (int)$phong->SoLuongGhe;

        return DB::transaction(function() use ($request, $maPhong, $mode, $maxSeats) {
            $currentCount = Ghe::where('MaPhong', $maPhong)->count();

            if ($mode === 'single') {
                $soGhe = strtoupper(trim($request->input('SoGhe')));
                if (empty($soGhe)) {
                    return back()->withErrors(['SoGhe' => 'Vui lòng nhập mã ghế'])->withInput();
                }

                if (Ghe::where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->exists()) {
                    return back()->withErrors(['SoGhe' => "Ghế $soGhe đã tồn tại"])->withInput();
                }

                if ($currentCount + 1 > $maxSeats) {
                    return back()->withErrors(['MaPhong' => 'Vượt quá số ghế tối đa của phòng'])->withInput();
                }

                Ghe::create(['MaPhong' => $maPhong, 'SoGhe' => $soGhe]);

                return redirect()->route('ghe.index')->with('success', 'Thêm ghế thành công');
            }

            // bulk mode
            $quantity = (int)$request->input('quantity', 0);
            $seats_per_row = (int)$request->input('seats_per_row', 10);
            if ($quantity < 1) {
                return back()->withErrors(['quantity' => 'Vui lòng nhập số lượng hợp lệ'])->withInput();
            }

            if ($currentCount + $quantity > $maxSeats) {
                return back()->withErrors(['MaPhong' => 'Vượt quá số ghế tối đa của phòng'])->withInput();
            }

            // Generate seat codes in format A1, A2, ... (simple generator)
            $letters = range('A', 'Z');
            $rowsNeeded = (int)ceil($quantity / $seats_per_row);
            $created = 0;

            for ($r = 0; $r < $rowsNeeded && $created < $quantity; $r++) {
                $rowLetter = $letters[$r % count($letters)];
                for ($c = 1; $c <= $seats_per_row && $created < $quantity; $c++) {
                    $seatCode = $rowLetter . $c;
                    if (Ghe::where('MaPhong', $maPhong)->where('SoGhe', $seatCode)->exists()) {
                        continue;
                    }
                    Ghe::create(['MaPhong' => $maPhong, 'SoGhe' => $seatCode]);
                    $created++;
                }
            }

            return redirect()->route('ghe.index')->with('success', 'Thêm nhiều ghế thành công');
        });
    }

    /**
     * Update: signature matches BaseCrudController::update(Request $request, $id)
     */
    public function update(Request $request, $id)
    {
        $request->validate(['SoGhe' => 'required|string|max:10']);

        $maPhong = $request->route('maPhong') ?? null;
        $soGhe = $request->route('soGhe') ?? null;
        if (is_null($maPhong) || is_null($soGhe)) {
            if (!is_null($id) && is_string($id)) {
                foreach (['|', '-', ':', ','] as $sep) {
                    if (strpos($id, $sep) !== false) {
                        list($maPhong, $soGhe) = explode($sep, $id, 2);
                        break;
                    }
                }
            }
        }

        if (isset($maPhong)) $maPhong = trim($maPhong);
        if (isset($soGhe)) $soGhe = trim($soGhe);

        if (empty($maPhong) || empty($soGhe)) {
            return back()->withErrors(['error' => 'Thiếu khóa MaPhong hoặc SoGhe'])->withInput();
        }

        $newSoGhe = strtoupper(trim($request->input('SoGhe')));

        if (Ghe::where('MaPhong', $maPhong)->where('SoGhe', $newSoGhe)->exists()) {
            return back()->withErrors(['SoGhe' => 'Mã ghế đã tồn tại'])->withInput();
        }

        DB::transaction(function () use ($maPhong, $soGhe, $newSoGhe) {
            Ghe::where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->delete();
            Ghe::create(['MaPhong' => $maPhong, 'SoGhe' => $newSoGhe]);
        });

        return redirect()->route('ghe.index')->with('success', 'Cập nhật ghế thành công');
    }

    /**
     * Destroy: signature matches BaseCrudController::destroy($id)
     */
    public function destroy($id)
    {
        $maPhong = request()->route('maPhong') ?? null;
        $soGhe = request()->route('soGhe') ?? null;
        if (is_null($maPhong) || is_null($soGhe)) {
            if (!is_null($id) && is_string($id)) {
                foreach (['|', '-', ':', ','] as $sep) {
                    if (strpos($id, $sep) !== false) {
                        list($maPhong, $soGhe) = explode($sep, $id, 2);
                        break;
                    }
                }
            }
        }
        if (isset($maPhong)) $maPhong = trim($maPhong);
        if (isset($soGhe)) $soGhe = trim($soGhe);

        $ghe = Ghe::where('MaPhong', $maPhong)->where('SoGhe', $soGhe)->firstOrFail();
        $ghe->delete();
        return redirect()->route('ghe.index')->with('success', 'Xóa ghế thành công');
    }

    /**
     * Edit: since we're keeping all interactions on one page, redirect to index with query params
     */
    public function edit($maPhong, $soGhe)
    {
        return redirect()->route('ghe.index', [
            'edit_ma_phong' => $maPhong,
            'edit_so_ghe' => $soGhe,
        ]);
    }
}
