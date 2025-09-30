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

    public function adminIndex()
    {
        $nguoiDungs = NguoiDung::with(['khachHang', 'nhanVien', 'taiKhoan'])->orderBy('created_at', 'desc')->paginate(15);
        return view('AdminNguoiDung', compact('nguoiDungs'));
    }

    public function edit($id)
    {
        $nguoiDung = NguoiDung::with(['khachHang', 'nhanVien', 'taiKhoan'])->findOrFail($id);
        return response()->json($nguoiDung);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'SoDienThoai' => 'required|string|max:15|unique:NguoiDung,SoDienThoai',
            'Email' => 'required|email|unique:NguoiDung,Email',
            'LoaiNguoiDung' => 'required|in:KhachHang,NhanVien',
        ]);

        $maNguoiDung = $this->generateMaNguoiDung();

        DB::transaction(function() use ($data, $maNguoiDung, $request, &$nguoiDung) {
            $nguoiDung = NguoiDung::create([
                'MaNguoiDung' => $maNguoiDung,
                'HoTen' => $data['HoTen'],
                'SoDienThoai' => $data['SoDienThoai'],
                'Email' => $data['Email'],
                'LoaiNguoiDung' => $data['LoaiNguoiDung'],
            ]);

            
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Thêm người dùng thành công!');
    }

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

            $this->capNhatBanGhiPhu($nguoiDung, $request);
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            $nguoiDung = NguoiDung::findOrFail($id);

            
            TaiKhoan::where('MaNguoiDung', $id)->delete();
            KhachHang::where('MaNguoiDung', $id)->delete();
            NhanVien::where('MaNguoiDung', $id)->delete();

            $nguoiDung->delete();
        });

        return redirect()->route('admin.nguoidung.index')->with('success', 'Xóa người dùng thành công!');
    }

    private function generateMaNguoiDung(): string
    {
        $prefix = 'ND' . date('Ymd');
        $last = NguoiDung::where('MaNguoiDung', 'like', $prefix . '%')
            ->orderBy('MaNguoiDung', 'desc')
            ->value('MaNguoiDung');

        if (! $last) {
            $sequence = 1;
        } else {
            $num = (int) substr($last, strlen($prefix));
            $sequence = $num + 1;
        }

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    private function taoBanGhiPhu(NguoiDung $nguoiDung, Request $request)
    {
       
    }

    private function capNhatBanGhiPhu(NguoiDung $nguoiDung, Request $request)
    {
        if ($nguoiDung->LoaiNguoiDung === 'KhachHang') {
            
            NhanVien::where('MaNguoiDung', $nguoiDung->MaNguoiDung)->delete();
            
        } else {
            
            KhachHang::where('MaNguoiDung', $nguoiDung->MaNguoiDung)->delete();

            
        }
    }

    public function createNguoiDungForRegistration(Request $request)
    {
        $data = $request->validate([
            'HoTen' => 'required|string|max:100',
            'SoDienThoai' => 'required|string|max:15|unique:NguoiDung,SoDienThoai',
            'Email' => 'required|email|unique:NguoiDung,Email',
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

            KhachHang::create([
                'MaNguoiDung' => $maNguoiDung,
                'DiemTichLuy' => 0,
            ]);
        });

        return response()->json(['MaNguoiDung' => $maNguoiDung], 201);
    }
}
