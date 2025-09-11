<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phim;

class PhimController extends BaseCrudController
{
    /**
     * Nếu BaseCrudController của bạn dùng $modelClass / $resource,
     * khai báo cho khớp để phần chung có thể sử dụng.
     */
    protected $modelClass = Phim::class;

    /**
     * Tên resource/view prefix.
     * Mình để 'phim' để khớp route resource('phim', ...) và view 'phim.index'.
     * Nếu project của bạn dùng 'phims' thì đổi lại thành 'phims'.
     */
    protected $resource = 'phim';

    /**
     * Quy tắc validate khớp với các cột trong migration và $fillable của Model.
     */
    protected $validationRules = [
        'ten_phim' => 'required|string|max:255',
        'thoi_luong' => 'nullable|integer',
        'ngay_khoi_chieu' => 'nullable|date',
        'nuoc_san_xuat' => 'nullable|string|max:50',
        'dinh_dang' => 'nullable|string|max:20',
        'mo_ta' => 'nullable|string',
        'dao_dien' => 'nullable|string|max:100',
        'duong_dan_poster' => 'nullable|string',
    ];

    /**
     * Hiển thị danh sách (với phân trang).
     */
    public function index(Request $request)
    {
        $perPage = max(1, (int) $request->get('per_page', 15));
        $items = Phim::orderBy('ma_phim', 'desc')->paginate($perPage);

        if ($request->wantsJson()) {
            return response()->json($items);
        }

        // View: resources/views/phim/index.blade.php
        return view('phim.index', compact('items'));
    }

    /**
     * Lưu bản ghi mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->validationRules);

        // Tạo mới — Model phải có $fillable tương ứng
        $phim = Phim::create($data);

        return redirect()->route('phim.index')->with('success', 'Tạo phim thành công');
    }

    /**
     * Cập nhật bản ghi.
     * $id sẽ là giá trị của primary key (ma_phim) — Model đã cấu hình $primaryKey.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate($this->validationRules);

        $phim = Phim::findOrFail($id);
        $phim->update($data);

        return redirect()->route('phim.index')->with('success', 'Cập nhật phim thành công');
    }

    /**
     * Xóa bản ghi.
     */
    public function destroy(Request $request, $id)
    {
        $phim = Phim::findOrFail($id);
        $phim->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('phim.index')->with('success', 'Xóa phim thành công');
    }
}
