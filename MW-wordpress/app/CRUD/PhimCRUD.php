<?php

namespace App\CRUD;

use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PhimCRUD
 * Đặt ở app/CRUD/PhimCRUD.php
 *
 * Lưu ý:
 * - File này là "CRUD helper" có thể được gọi từ routes hoặc controller.
 * - Trả về JsonResponse để tiện test nhanh.
 */
class PhimCRUD
{
    // Lấy tất cả phim
    public function index()
    {
        $phims = Phim::orderBy('MaPhim', 'desc')->get();
        return response()->json(['success' => true, 'data' => $phims], 200);
    }

    // Lấy phim theo id
    public function show($id)
    {
        $phim = Phim::find($id);
        if (!$phim) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy phim'], 404);
        }
        return response()->json(['success' => true, 'data' => $phim], 200);
    }

    // Tạo phim mới
    public function store(Request $request)
    {
        $rules = [
            'TenPhim' => 'required|string|max:255',
            'ThoiLuong' => 'nullable|integer|min:0',
            'NgayKhoiChieu' => 'nullable|date',
            'NuocSanXuat' => 'nullable|string|max:255',
            'DinhDang' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string',
            'DaoDien' => 'nullable|string|max:255',
            'DuongDanPoster' => 'nullable|string|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Mass assignment: đảm bảo App\Models\Phim có $fillable chứa các field này
        $phim = Phim::create($data);

        return response()->json(['success' => true, 'data' => $phim], 201);
    }

    // Cập nhật phim
    public function update(Request $request, $id)
    {
        $phim = Phim::find($id);
        if (!$phim) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy phim'], 404);
        }

        $rules = [
            'TenPhim' => 'sometimes|required|string|max:255',
            'ThoiLuong' => 'nullable|integer|min:0',
            'NgayKhoiChieu' => 'nullable|date',
            'NuocSanXuat' => 'nullable|string|max:255',
            'DinhDang' => 'nullable|string|max:255',
            'MoTa' => 'nullable|string',
            'DaoDien' => 'nullable|string|max:255',
            'DuongDanPoster' => 'nullable|string|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $phim->update($validator->validated());

        return response()->json(['success' => true, 'data' => $phim], 200);
    }

    // Xóa phim
    public function destroy($id)
    {
        $phim = Phim::find($id);
        if (!$phim) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy phim'], 404);
        }
        $phim->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thành công'], 200);
    }
}
