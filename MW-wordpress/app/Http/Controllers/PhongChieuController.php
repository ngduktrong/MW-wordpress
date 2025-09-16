<?php

namespace App\Http\Controllers;

use App\Models\PhongChieu;
use Illuminate\Http\Request;

class PhongChieuController extends BaseCrudController
{
    protected $model = PhongChieu::class;
    protected $primaryKey = 'MaPhong';

    public function index()
    {
        $phongChieus = parent::index();
        
        // Kiểm tra nếu có tham số edit trong URL
        $editId = request()->get('edit');
        $phongChieu = null;
        
        if ($editId) {
            $phongChieu = $this->model::find($editId);
        }
        
        return view('AdminPhongChieu', compact('phongChieus', 'phongChieu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenPhong' => 'required|unique:PhongChieu|max:255',
            'SoLuongGhe' => 'required|integer|min:1',
            'LoaiPhong' => 'required|max:50'
        ]);

        $result = parent::store($request);
        
        return redirect()->route('admin.phongchieu.index')
                         ->with('success', 'Thêm phòng chiếu thành công');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenPhong' => 'required|max:255|unique:PhongChieu,TenPhong,' . $id . ',MaPhong',
            'SoLuongGhe' => 'required|integer|min:1',
            'LoaiPhong' => 'required|max:50'
        ]);

        $result = parent::update($request, $id);
        
        return redirect()->route('admin.phongchieu.index')
                         ->with('success', 'Cập nhật phòng chiếu thành công');
    }

    public function destroy($id)
    {
        $result = parent::destroy($id);
        
        return redirect()->route('admin.phongchieu.index')
                         ->with('success', 'Xóa phòng chiếu thành công');
    }
}