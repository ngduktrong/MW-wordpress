<?php

namespace App\Http\Controllers;

use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\PhongChieu;
use Illuminate\Http\Request;

class SuatChieuController extends BaseCrudController
{
    protected $model = SuatChieu::class;
    protected $primaryKey = 'MaSuatChieu';

    public function index()
    {
        $suatChieus = parent::index();
        $phims = Phim::all();
        $phongChieus = PhongChieu::all();
        
        // Kiểm tra nếu có tham số edit trong URL
        $editId = request()->get('edit');
        $suatChieu = null;
        
        if ($editId) {
            $suatChieu = $this->model::find($editId);
        }
        
        return view('AdminSuatChieu', compact('suatChieus', 'phims', 'phongChieus', 'suatChieu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaPhim' => 'required|exists:Phim,MaPhim',
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'NgayGioChieu' => 'required|date'
        ]);

        $result = parent::store($request);
        
        return redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Thêm suất chiếu thành công');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'MaPhim' => 'required|exists:Phim,MaPhim',
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'NgayGioChieu' => 'required|date'
        ]);

        $result = parent::update($request, $id);
        
        return redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Cập nhật suất chiếu thành công');
    }

    public function destroy($id)
    {
        $result = parent::destroy($id);
        
        return redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Xóa suất chiếu thành công');
    }
}