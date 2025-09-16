<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseCrudController extends Controller
{
    /**
     * Model class mà controller con sẽ gán, ví dụ: App\Models\Phim::class
     */
    protected $model;

    /**
     * Primary key của model (chuỗi)
     */
    protected $primaryKey = 'id';

    // Lấy toàn bộ dữ liệu
    public function index()
    {
        return $this->model::all();
    }

    // Thêm mới (trả về created model)
    public function store(Request $request)
    {
        $item = $this->model::create($request->all());
        return $item;
    }

    // Xem chi tiết
    public function show($id)
    {
        return $this->model::findOrFail($id);
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    // Xóa
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }
}
