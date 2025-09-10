<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // dùng cho login/auth
use Illuminate\Notifications\Notifiable;

class TaiKhoan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tai_khoan';   // tên bảng
    protected $primaryKey = 'ten_dang_nhap'; // khóa chính
    public $incrementing = false;     // vì không phải AUTO_INCREMENT
    protected $keyType = 'string';    // khóa chính dạng chuỗi
    public $timestamps = false;       // không có created_at, updated_at

    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'loai_tai_khoan',
        'ma_nguoi_dung',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    // Constants cho loại tài khoản
    const ADMIN = 'admin';
    const USER = 'user';

    // Mutator: đảm bảo loại tài khoản hợp lệ
    public function setLoaiTaiKhoanAttribute($value)
    {
        $this->attributes['loai_tai_khoan'] =
            strtolower($value) === self::ADMIN ? self::ADMIN : self::USER;
    }

    // Quan hệ: một tài khoản thuộc về một người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }
}
