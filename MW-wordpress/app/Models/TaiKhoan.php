<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'tai_khoan';
    protected $primaryKey = 'TenDangNhap'; // Nếu username là khóa chính
    public $incrementing = false;          // Vì khóa chính không phải auto-increment
    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'LoaiTaiKhoan',
        'MaNguoiDung',
    ];

    // Constants cho loại tài khoản
    const ADMIN = 'admin';
    const USER = 'user';

    // Setter LoaiTaiKhoan để đảm bảo hợp lệ
    public function setLoaiTaiKhoanAttribute($value)
    {
        $this->attributes['LoaiTaiKhoan'] = strtolower($value) === self::ADMIN ? self::ADMIN : self::USER;
    }

    // Ẩn mật khẩu khi toArray / toJson
    protected $hidden = ['MatKhau'];
}
