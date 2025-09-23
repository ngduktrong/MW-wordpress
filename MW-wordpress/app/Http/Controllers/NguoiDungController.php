<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\TaiKhoan;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NguoiDungController extends BaseCrudController
{
    protected $model = NguoiDung::class;
    protected $primaryKey = 'MaNguoiDung';

    /**
     * Hiển thị danh sách (admin)
     */
    public function adminIndex()
    {
        // Dùng paginate để tránh load quá nhiều bản ghi
        $nguoiDungs = NguoiDung::with(['khachHang', 'nhanVien', 'taiKhoan'])->orderBy('created_at', 'desc')->paginate(15);
        return view('AdminNguoiDung', compact('nguoiDungs'));
    }

    /**
     * Trả dữ liệu để edit (AJAX)
     */
    public function edit($id)
    {
        $nguoiDung = NguoiDung::with(['khachHang', 'nhanVien', 'taiKhoan'])->findOrFail($id);
        return response()->json($nguoiDung);
    }

    /**
     * Store: tạo mới người dùng + tự động tạo tài khoản + bản ghi phụ
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'SoDienThoai' => 'required|string|max:15|unique:NguoiDung,SoDienThoai',
            'Email' => 'required|email|unique:NguoiDung,Email',
            'LoaiNguoiDung' => 'required|in:KhachHang,NhanVien',
        ]);

        // Sinh mã người dùng duy nhất
        $maNguoiDung = $this->generateMaNguoiDung();

        DB::transaction(function() use ($data, $maNguoiDung, $request, &$nguoiDung) {
            $nguoiDung = NguoiDung::create([
                'MaNguoiDung' => $maNguoiDung,
                'HoTen' => $data['HoTen'],
                'SoDienThoai' => $data['SoDienThoai'],
                'Email' => $data['Email'],
                'LoaiNguoiDung' => $data['LoaiNguoiDung'],
            ]);

            // Tự động tạo tài khoản đăng nhập (mật khẩu tạm thời ngẫu nhiên)
            $this->taoTaiKhoanTuDong($nguoiDung);

            // Tạo bản ghi phụ (KhachHang hoặc NhanVien)
            $this->taoBanGhiPhu($nguoiDung, $request);
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Update: cập nhật thông tin người dùng và bảng phụ
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'SoDienThoai' => 'required|string|max:15|unique:NguoiDung,SoDienThoai,' . $id . ',MaNguoiDung',
            'Email' => 'required|email|unique:NguoiDung,Email,' . $id . ',MaNguoiDung',
            'LoaiNguoiDung' => 'required|in:KhachHang,NhanVien',
        ]);

        DB::transaction(function() use ($data, $id, $request, &$nguoiDung) {
            $nguoiDung = NguoiDung::findOrFail($id);
            $nguoiDung->update([
                'HoTen' => $data['HoTen'],
                'SoDienThoai' => $data['SoDienThoai'],
                'Email' => $data['Email'],
                'LoaiNguoiDung' => $data['LoaiNguoiDung'],
            ]);

            // Cập nhật hoặc tạo/xóa bảng phụ nếu LoaiNguoiDung thay đổi
            $this->capNhatBanGhiPhu($nguoiDung, $request);
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Destroy: xóa người dùng và các bản ghi liên quan
     */
    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            $nguoiDung = NguoiDung::findOrFail($id);

            // Xóa các bản ghi liên quan nếu không có cascade FK
            TaiKhoan::where('MaNguoiDung', $id)->delete();
            KhachHang::where('MaNguoiDung', $id)->delete();
            NhanVien::where('MaNguoiDung', $id)->delete();

            $nguoiDung->delete();
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Xóa người dùng thành công!');
    }

    /**
     * Sinh mã người dùng theo định dạng: NDYYYYMMDDNNN (tăng dần theo ngày)
     */
    private function generateMaNguoiDung(): string
    {
        $prefix = 'ND' . date('Ymd');
        // Lấy mã lớn nhất bắt đầu bằng prefix
        $last = NguoiDung::where('MaNguoiDung', 'like', $prefix . '%')
            ->orderBy('MaNguoiDung', 'desc')
            ->value('MaNguoiDung');

        if (! $last) {
            $sequence = 1;
        } else {
            // last có dạng NDYYYYMMDDNNN -> lấy phần NNN
            $num = (int) substr($last, strlen($prefix));
            $sequence = $num + 1;
        }

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Tạo tài khoản tự động từ MaNguoiDung (tên đăng nhập, mật khẩu tạm thời)
     */
    private function taoTaiKhoanTuDong(NguoiDung $nguoiDung)
    {
        // Tạo TenDangNhap từ email (phần trước @) + đảm bảo duy nhất
        $tenDangNhap = explode('@', $nguoiDung->Email)[0] ?? $nguoiDung->MaNguoiDung;
        $original = $tenDangNhap;
        $counter = 1;
        while (TaiKhoan::where('TenDangNhap', $tenDangNhap)->exists()) {
            $tenDangNhap = $original . $counter;
            $counter++;
        }

        // Sinh mật khẩu tạm thời
        $rawPassword = Str::random(10);
        $matKhau = Hash::make($rawPassword);

        $loaiTaiKhoan = $nguoiDung->LoaiNguoiDung === 'NhanVien' ? 'admin' : 'user';

        TaiKhoan::create([
            'TenDangNhap' => $tenDangNhap,
            'MatKhau' => $matKhau,
            'LoaiTaiKhoan' => $loaiTaiKhoan,
            'MaNguoiDung' => $nguoiDung->MaNguoiDung,
        ]);

        // TODO: Gửi email chứa mật khẩu tạm thời và yêu cầu đổi mật khẩu lần đầu
    }

    /**
     * Tạo bản ghi phụ KhachHang hoặc NhanVien dựa trên LoaiNguoiDung
     */
    private function taoBanGhiPhu(NguoiDung $nguoiDung, Request $request)
    {
        if ($nguoiDung->LoaiNguoiDung === 'KhachHang') {
            // Nếu có thêm field như DiemTichLuy có thể lấy từ request
            KhachHang::create([
                'MaNguoiDung' => $nguoiDung->MaNguoiDung,
                'DiemTichLuy' => $request->input('DiemTichLuy', 0),
            ]);
        } else {
            NhanVien::create([
                'MaNguoiDung' => $nguoiDung->MaNguoiDung,
                'ChucVu' => $request->input('ChucVu', 'Nhân viên'),
                'Luong' => $request->input('Luong', 0),
                'VaiTro' => $request->input('VaiTro', 'BanVe'),
            ]);
        }
    }

    /**
     * Cập nhật bảng phụ khi thông tin LoaiNguoiDung thay đổi hoặc update thông tin thêm
     */
    private function capNhatBanGhiPhu(NguoiDung $nguoiDung, Request $request)
    {
        // Nếu là KhachHang
        if ($nguoiDung->LoaiNguoiDung === 'KhachHang') {
            // Xóa bản ghi NhanVien nếu tồn tại
            NhanVien::where('MaNguoiDung', $nguoiDung->MaNguoiDung)->delete();

            // Tạo hoặc cập nhật KhachHang
            $kh = KhachHang::firstOrNew(['MaNguoiDung' => $nguoiDung->MaNguoiDung]);
            $kh->DiemTichLuy = $request->input('DiemTichLuy', $kh->DiemTichLuy ?? 0);
            $kh->save();
        } else {
            // Nếu là NhanVien
            KhachHang::where('MaNguoiDung', $nguoiDung->MaNguoiDung)->delete();

            $nv = NhanVien::firstOrNew(['MaNguoiDung' => $nguoiDung->MaNguoiDung]);
            $nv->ChucVu = $request->input('ChucVu', $nv->ChucVu ?? 'Nhân viên');
            $nv->Luong = $request->input('Luong', $nv->Luong ?? 0);
            $nv->VaiTro = $request->input('VaiTro', $nv->VaiTro ?? 'BanVe');
            $nv->save();
        }
    }

    /**
     * Placeholder: Tạo MaNguoiDung khi user đăng ký ở front-end.
     * Hiện chỉ tạo người dùng + KhachHang (chưa tạo TaiKhoan).
     * Sau này phần đăng ký sẽ dùng endpoint này hoặc gọi helper để sinh mã.
     */
    public function createNguoiDungForRegistration(Request $request)
    {
        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'SoDienThoai' => 'required|string|max:15|unique:NguoiDung,SoDienThoai',
            'Email' => 'required|email|unique:NguoiDung,Email',
            // Mặc định LoaiNguoiDung = KhachHang
        ]);

        $maNguoiDung = $this->generateMaNguoiDung();

        DB::transaction(function() use ($data, $maNguoiDung, &$nguoiDung) {
            $nguoiDung = NguoiDung::create([
                'MaNguoiDung' => $maNguoiDung,
                'HoTen' => $data['HoTen'],
                'SoDienThoai' => $data['SoDienThoai'],
                'Email' => $data['Email'],
                'LoaiNguoiDung' => 'KhachHang',
            ]);

            // Tạo KhachHang liên quan. (Chưa tạo TaiKhoan: phần đăng ký sẽ xử lý mật khẩu)
            KhachHang::create([
                'MaNguoiDung' => $maNguoiDung,
                'DiemTichLuy' => 0,
            ]);
        });

        // Trả về mã người dùng để front-end dùng tiếp (ví dụ gắn vào luồng đăng ký)
        return response()->json(['MaNguoiDung' => $maNguoiDung], 201);
    }
}
