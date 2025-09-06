<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';        // Tên bảng trong DB
    protected $primaryKey = 'MaNguoiDung';  // Khóa chính
    public $timestamps = false;             // Nếu bảng không có created_at, updated_at

    protected $fillable = [
        'HoTen',
        'SoDienThoai',
        'Email',
        'LoaiNguoiDung',
    ];

    // Hằng số loại người dùng
    const LOAI_KHACHHANG = 'KhachHang';
    const LOAI_NHANVIEN = 'NhanVien';
}
