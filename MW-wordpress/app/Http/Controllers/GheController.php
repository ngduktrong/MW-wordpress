<?php

namespace App\Http\Controllers;

use App\Models\Ghe;
use App\Models\PhongChieu;
use Illuminate\Http\Request;

class GheController extends BaseCrudController
{
    protected $model = Ghe::class;

    public function __construct()
    {
        // Không cần gọi parent constructor
    }

    /**
     * Hiển thị danh sách ghế (view chung AdminGhe)
     */
    public function index()
    {
        $ghes = Ghe::with('phongChieu')->get();
        $phongChieus = PhongChieu::all();
        return view('AdminGhe', compact('ghes', 'phongChieus'));
    }

    /**
     * Trả view để edit (sử dụng cùng view index nhưng có $editingGhe)
     * Route: GET /ghe/{maPhong}/{soGhe}/edit
     */
    public function edit($maPhong, $soGhe)
    {
        $editingGhe = Ghe::where('MaPhong', $maPhong)
                         ->where('SoGhe', $soGhe)
                         ->firstOrFail();

        $ghes = Ghe::with('phongChieu')->get();
        $phongChieus = PhongChieu::all();

        return view('AdminGhe', compact('ghes', 'phongChieus', 'editingGhe'));
    }

    /**
     * Thêm ghế mới
     * Route: POST /ghe
     */
    public function store(Request $request)
    {
        $request->validate([
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'SoGhe'   => 'required|string|max:5',
        ]);

        $exists = Ghe::where('MaPhong', $request->MaPhong)
                     ->where('SoGhe', $request->SoGhe)
                     ->first();

        if ($exists) {
            return redirect()->route('ghe.index')->with('error', 'Ghế đã tồn tại trong phòng này');
        }

        Ghe::create([
            'MaPhong' => $request->MaPhong,
            'SoGhe'   => $request->SoGhe,
        ]);

        return redirect()->route('ghe.index')->with('success', 'Thêm ghế thành công');
    }

    /**
     * Cập nhật ghế
     * CHÚ Ý: chữ ký tương thích với BaseCrudController
     * Route: PUT /ghe/{maPhong}/{soGhe}
     */
    public function update(Request $request, $maPhong)
    {
        // Lấy soGhe cũ từ route (route phải có {maPhong}/{soGhe})
        $soGheOld = $request->route('soGhe');

        $request->validate([
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'SoGhe'   => 'required|string|max:5',
        ]);

        $ghe = Ghe::where('MaPhong', $maPhong)
                  ->where('SoGhe', $soGheOld)
                  ->firstOrFail();

        // Nếu đổi key thì check trùng
        if ($request->MaPhong != $maPhong || $request->SoGhe != $soGheOld) {
            $exists = Ghe::where('MaPhong', $request->MaPhong)
                         ->where('SoGhe', $request->SoGhe)
                         ->first();
            if ($exists) {
                return redirect()->route('ghe.index')->with('error', 'Ghế đã tồn tại trong phòng này');
            }
        }

        // Cập nhật an toàn (gán rồi save để tránh vấn đề với composite key)
        $ghe->MaPhong = $request->MaPhong;
        $ghe->SoGhe   = $request->SoGhe;
        $ghe->save();

        return redirect()->route('ghe.index')->with('success', 'Cập nhật ghế thành công');
    }

    /**
     * Xóa ghế
     * CHÚ Ý: chữ ký tương thích với BaseCrudController
     * Route: DELETE /ghe/{maPhong}/{soGhe}
     */
    public function destroy($maPhong)
    {
        $soGhe = request()->route('soGhe');

        $ghe = Ghe::where('MaPhong', $maPhong)
                  ->where('SoGhe', $soGhe)
                  ->firstOrFail();

        $ghe->delete();

        return redirect()->route('ghe.index')->with('success', 'Xóa ghế thành công');
    }
}
