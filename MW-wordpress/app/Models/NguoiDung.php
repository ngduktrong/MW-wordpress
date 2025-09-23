<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NguoiDung extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'NguoiDung';

    // Khóa chính của bảng
    protected $primaryKey = 'MaNguoiDung';

    // Các trường có thể gán giá trị
    protected $fillable = [
        'HoTen',
        'SoDienThoai',
        'Email',
        'LoaiNguoiDung'
    ];

    // Tự động quản lý timestamps
    public $timestamps = true;

    // Định dạng kiểu dữ liệu cho các thuộc tính
    protected $casts = [
        'LoaiNguoiDung' => 'string',
    ];

    /**
     * Quan hệ 1-1 với bảng KhachHang
     */
    public function khachHang(): HasOne
    {
        return $this->hasOne(KhachHang::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-1 với bảng NhanVien
     */
    public function nhanVien(): HasOne
    {
        return $this->hasOne(NhanVien::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Quan hệ 1-1 với bảng TaiKhoan
     */
    public function taiKhoan(): HasOne
    {
        return $this->hasOne(TaiKhoan::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Scope để lấy khách hàng
     */
    public function scopeKhachHang($query)
    {
        return $query->where('LoaiNguoiDung', 'KhachHang');
    }

    /**
     * Scope để lấy nhân viên
     */
    public function scopeNhanVien($query)
    {
        return $query->where('LoaiNguoiDung', 'NhanVien');
    }

    /**
     * Kiểm tra xem người dùng có phải là khách hàng không
     */
    public function isKhachHang(): bool
    {
        return $this->LoaiNguoiDung === 'KhachHang';
    }

    /**
     * Kiểm tra xem người dùng có phải là nhân viên không
     */
    public function isNhanVien(): bool
    {
        return $this->LoaiNguoiDung === 'NhanVien';
    }

    /**
     * Accessor cho tên đầy đủ (viết hoa chữ cái đầu)
     */
    public function getHoTenFormattedAttribute(): string
    {
        return mb_convert_case($this->HoTen, MB_CASE_TITLE, "UTF-8");
    }
}
