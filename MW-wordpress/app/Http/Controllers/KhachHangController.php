<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhachHang;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Validator;

class KhachHangController extends BaseCrudController
{
    /**
     * Hiển thị danh sách (trả view hoặc JSON tuỳ app của bạn)
     */
     public function index()
    {
        // Lấy danh sách khách hàng kèm thông tin người dùng với phân trang
        $khachhangs = KhachHang::with([
            'nguoiDung' => function($query) {
                $query->select('MaNguoiDung', 'HoTen', 'SoDienThoai', 'Email');
            }
        ])->orderBy('MaNguoiDung')->paginate(20); // Phân trang 20 items/trang

        // Trả về view AdminKhachHang (file nằm trực tiếp trong views)
        return view('AdminKhachHang', ['khachhangs' => $khachhangs]);
    }

    /**
     * Tạo Khách hàng mới
     */
    public function store(Request $request)
    {
        $data = $request->only(['MaNguoiDung', 'DiemTichLuy']);

        $validator = Validator::make($data, [
            'MaNguoiDung' => 'required|integer',
            'DiemTichLuy' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ma = (int) $data['MaNguoiDung'];

        // Kiểm tra người dùng tồn tại
        $nguoi = NguoiDung::where('MaNguoiDung', $ma)->first();
        if (!$nguoi) {
            return response()->json(['success' => false, 'message' => 'Mã người dùng không tồn tại.'], 404);
        }

        // Kiểm tra đã có trong KhachHang chưa
        if (KhachHang::where('MaNguoiDung', $ma)->exists()) {
            return response()->json(['success' => false, 'message' => 'Mã người dùng này đã được gán làm Khách hàng.'], 422);
        }

        $kh = new KhachHang();
        // Gán thủ công MaNguoiDung để tránh rủi ro mass-assignment
        $kh->MaNguoiDung = $ma;
        $kh->DiemTichLuy = isset($data['DiemTichLuy']) ? (int)$data['DiemTichLuy'] : 0;
        $kh->save();

        return response()->json(['success' => true, 'message' => 'Tạo khách hàng thành công.', 'khachhang' => $kh->load('nguoiDung')], 201);
    }

    /**
     * Cập nhật DiemTichLuy của khách hàng
     * $id là MaNguoiDung (primary key của KhachHang)
     */
    public function update(Request $request, $id)
    {
        $ma = (int) $id;

        $kh = KhachHang::where('MaNguoiDung', $ma)->first();
        if (!$kh) {
            return response()->json(['success' => false, 'message' => 'Khách hàng không tồn tại.'], 404);
        }

        $data = $request->only(['DiemTichLuy']);
        $validator = Validator::make($data, [
            'DiemTichLuy' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $kh->DiemTichLuy = (int) $data['DiemTichLuy'];
        $kh->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'khachhang' => $kh->load('nguoiDung')], 200);
    }

    /**
     * Xóa khách hàng
     */
    public function destroy($id)
    {
        $ma = (int) $id;
        $kh = KhachHang::where('MaNguoiDung', $ma)->first();
        if (!$kh) {
            return response()->json(['success' => false, 'message' => 'Khách hàng không tồn tại.'], 404);
        }

        $kh->delete();
        return response()->json(['success' => true, 'message' => 'Xóa thành công.'], 200);
    }

    /**
     * Kiểm tra mã người dùng (dành cho AJAX)
     */
    public function checkUser($maNguoiDung)
    {
        $ma = (int) $maNguoiDung;
        $isEdit = request()->has('is_edit'); // Thêm tham số để biết đang edit hay create

        // Tối ưu: chỉ lấy các trường cần thiết
        $nguoi = NguoiDung::select('MaNguoiDung', 'HoTen', 'SoDienThoai', 'Email')
                ->where('MaNguoiDung', $ma)
                ->first();

        if (!$nguoi) {
            return response()->json(['valid' => false, 'message' => 'Mã người dùng không tồn tại.'], 404);
        }

        $existsInKhachHang = KhachHang::where('MaNguoiDung', $ma)->exists();

        // Logic khác nhau cho create vs edit
        if (!$isEdit && $existsInKhachHang) {
            return response()->json(['valid' => false, 'message' => 'Mã người dùng này đã được gán làm Khách hàng.'], 422);
        }

        if ($isEdit && !$existsInKhachHang) {
            return response()->json(['valid' => false, 'message' => 'Mã người dùng chưa được đăng ký làm Khách hàng.'], 422);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'MaNguoiDung' => $nguoi->MaNguoiDung,
                'HoTen' => $nguoi->HoTen ?? null,
                'SoDienThoai' => $nguoi->SoDienThoai ?? null,
                'Email' => $nguoi->Email ?? null,
            ]
        ], 200);
    }
}