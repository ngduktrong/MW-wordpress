<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TaiKhoan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'TaiKhoan';
    protected $primaryKey = 'TenDangNhap';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'LoaiTaiKhoan',
        'MaNguoiDung'
    ];

    protected $hidden = [
        'MatKhau',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    /**
     * Quan hệ với bảng NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Kiểm tra xem tài khoản có phải là admin không
     */
    public function isAdmin()
    {
        return $this->LoaiTaiKhoan === 'admin';
    }

    /**
     * Kiểm tra xem tài khoản có phải là user thông thường không
     */
    public function isUser()
    {
        return $this->LoaiTaiKhoan === 'user';
    }

    /**
     * Scope để lấy tài khoản admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('LoaiTaiKhoan', 'admin');
    }

    /**
     * Scope để lấy tài khoản user
     */
    public function scopeUser($query)
    {
        return $query->where('LoaiTaiKhoan', 'user');
    }

    /**
     * Quan hệ với bảng NhanVien thông qua NguoiDung (nếu là nhân viên)
     */
    public function nhanVien()
    {
        return $this->hasOneThrough(
            NhanVien::class,
            NguoiDung::class,
            'MaNguoiDung', // Khóa ngoại trên bảng NguoiDung
            'MaNguoiDung', // Khóa ngoại trên bảng NhanVien
            'MaNguoiDung', // Khóa chính trên bảng TaiKhoan
            'MaNguoiDung'  // Khóa chính trên bảng NguoiDung
        );
    }

    /**
     * Quan hệ với bảng KhachHang thông qua NguoiDung (nếu là khách hàng)
     */
    public function khachHang()
    {
        return $this->hasOneThrough(
            KhachHang::class,
            NguoiDung::class,
            'MaNguoiDung', // Khóa ngoại trên bảng NguoiDung
            'MaNguoiDung', // Khóa ngoại trên bảng KhachHang
            'MaNguoiDung', // Khóa chính trên bảng TaiKhoan
            'MaNguoiDung'  // Khóa chính trên bảng NguoiDung
        );
    }

    /**
     * Lấy thông tin hóa đơn của khách hàng (nếu có)
     */
    public function hoaDon()
    {
        return $this->hasManyThrough(
            HoaDon::class,
            NguoiDung::class,
            'MaNguoiDung', // Khóa ngoại trên bảng NguoiDung
            'MaKhachHang', // Khóa ngoại trên bảng HoaDon
            'MaNguoiDung', // Khóa chính trên bảng TaiKhoan
            'MaNguoiDung'  // Khóa chính trên bảng NguoiDung
        );
    }
}