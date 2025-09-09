<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PhimRepositoryInterface;
use Illuminate\Http\Request;

class PhimController extends Controller
{
    protected PhimRepositoryInterface $phimRepo;

    public function __construct(PhimRepositoryInterface $phimRepo)
    {
        $this->phimRepo = $phimRepo;
    }

    // GET /phims or /phims?q=...
    public function index(Request $request)
    {
        if ($request->has('q')) {
            $data = $this->phimRepo->findByTitle($request->get('q'));
            return response()->json($data);
        }

        $perPage = (int) $request->get('per_page', 15);
        $data = $this->phimRepo->paginate($perPage);
        return response()->json($data);
    }

    // GET /phims/{id}
    public function show($id)
    {
        $phim = $this->phimRepo->find($id);
        if (! $phim) {
            return response()->json(['message' => 'Phim không tồn tại'], 404);
        }
        return response()->json($phim);
    }

    // POST /phims
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

        $phim = $this->phimRepo->create($validated);
        return response()->json($phim, 201);
    }

    // PUT/PATCH /phims/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'TenPhim' => 'sometimes|required|string|max:100',
            'ThoiLuong' => 'sometimes|required|integer|min:1',
            'NgayKhoiChieu' => 'sometimes|required|date',
            'NuocSanXuat' => 'sometimes|required|string|max:50',
            'DinhDang' => 'sometimes|required|string|max:20',
            'MoTa' => 'nullable|string',
            'DaoDien' => 'sometimes|required|string|max:100',
            'DuongDanPoster' => 'nullable|string',
        ]);

        $updated = $this->phimRepo->update($id, $validated);
        if (! $updated) {
            return response()->json(['message' => 'Cập nhật thất bại hoặc phim không tồn tại'], 404);
        }
        return response()->json($updated);
    }

    // DELETE /phims/{id}
    public function destroy($id)
    {
        $deleted = $this->phimRepo->delete($id);
        if (! $deleted) {
            return response()->json(['message' => 'Xóa thất bại hoặc phim không tồn tại'], 404);
        }
        return response()->json(['message' => 'Xóa thành công']);
    }
}
