<?php

namespace App\Http\Controllers;

use App\Models\Phim;
use Illuminate\Http\Request;


class PhimController extends BaseCrudController
{
    // Gán model và primaryKey
    protected $model = Phim::class;
    protected $primaryKey = 'MaPhim';

    // Hiển thị trang AdminPhim
    public function showAdminPage()
    {
        // Lấy tất cả phim, sắp xếp theo MaPhim
        $phims = Phim::orderBy('MaPhim', 'asc')->get();
        return view('AdminPhim', compact('phims'));
    }

    // Override store để validate và redirect về trang admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenPhim' => 'required|string|max:100',
            'ThoiLuong' => 'required|integer|min:1',
            'NgayKhoiChieu' => 'required|date',
            'NuocSanXuat' => 'required|string|max:50',
            'DinhDang' => 'required|string|max:20',
            'MoTa' => 'nullable|string',
            'DaoDien' => 'required|string|max:100',
            'DuongDanPoster' => 'nullable|string',
        ]);

        $phim = $this->model::create($validated);

        return redirect()->route('admin.phim')->with('success', 'Thêm phim thành công');
    }

    // Override update để validate, cập nhật và redirect về admin
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'TenPhim' => 'required|string|max:100',
            'ThoiLuong' => 'required|integer|min:1',
            'NgayKhoiChieu' => 'required|date',
            'NuocSanXuat' => 'required|string|max:50',
            'DinhDang' => 'required|string|max:20',
            'MoTa' => 'nullable|string',
            'DaoDien' => 'required|string|max:100',
            'DuongDanPoster' => 'nullable|string',
        ]);

        $item = $this->model::findOrFail($id);
        $item->update($validated);

        return redirect()->route('admin.phim')->with('success', 'Cập nhật phim thành công');
    }

    // Override destroy: xóa và redirect về admin
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.phim')->with('success', 'Xóa phim thành công');
    }
}
