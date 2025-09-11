<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    use HasFactory;

    protected $table = 'NguoiDung';
    protected $primaryKey = 'MaNguoiDung';
    public $timestamps = false;

    protected $fillable = [
        'HoTen',
        'SoDienThoai',
        'Email',
        'LoaiNguoiDung'
    ];

    /**
     * Mối quan hệ với bảng KhachHang
     */
    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng NhanVien
     */
    public function nhanVien()
    {
        return $this->hasOne(NhanVien::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng TaiKhoan
     */
    public function taiKhoan()
    {
        return $this->hasOne(TaiKhoan::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng HoaDon (qua khách hàng)
     */
    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'MaKhachHang', 'MaNguoiDung');
    }

    /**
     * Kiểm tra xem người dùng có phải là khách hàng không
     */
    public function isKhachHang()
    {
        return $this->LoaiNguoiDung === 'KhachHang';
    }

    /**
     * Kiểm tra xem người dùng có phải là nhân viên không
     */
    public function isNhanVien()
    {
        return $this->LoaiNguoiDung === 'NhanVien';
    }

    /**
     * Scope để lọc theo loại người dùng
     */
    public function scopeLoaiNguoiDung($query, $loai)
    {
        return $query->where('LoaiNguoiDung', $loai);
    }

    /**
     * Scope để tìm kiếm theo số điện thoại
     */
    public function scopeSoDienThoai($query, $soDienThoai)
    {
        return $query->where('SoDienThoai', $soDienThoai);
    }

    /**
     * Scope để tìm kiếm theo email
     */
    public function scopeEmail($query, $email)
    {
        return $query->where('Email', $email);
    }
}