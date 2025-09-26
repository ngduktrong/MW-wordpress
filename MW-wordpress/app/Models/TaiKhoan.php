<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // THAY ĐỔI DÒNG NÀY
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Authenticatable // THAY ĐỔI: extends Authenticatable thay vì Model
{
    protected $table = 'TaiKhoan';

    // Primary key là TenDangNhap (string)
    protected $primaryKey = 'TenDangNhap';
    public $incrementing = false;
    protected $keyType = 'string';

    // Cho phép mass assignment cho các trường cần thiết
    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'LoaiTaiKhoan',
        'MaNguoiDung',
    ];

    // Nếu cần casts
    protected $casts = [
        'MaNguoiDung' => 'integer',
    ];

    // Thêm các method cần thiết cho Authenticatable
    public function getAuthIdentifierName()
    {
        return 'TenDangNhap';
    }

    public function getAuthIdentifier()
    {
        return $this->TenDangNhap;
    }

    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // Mutator: tự hash mật khẩu khi set (nếu chưa được hash)
    public function setMatKhauAttribute($value)
    {
        if (empty($value)) {
            // không thay đổi nếu trống (chủ động xử lý ở controller)
            $this->attributes['MatKhau'] = $this->attributes['MatKhau'] ?? null;
            return;
        }

        // nếu đã là bcrypt (bắt đầu bằng $2y$ hoặc $2a$) thì giữ nguyên
        if (is_string($value) && (str_starts_with($value, '$2y$') || str_starts_with($value, '$2a$'))) {
            $this->attributes['MatKhau'] = $value;
        } else {
            $this->attributes['MatKhau'] = Hash::make($value);
        }
    }

    // Quan hệ tới NguoiDung (nếu muốn dùng later)
    public function nguoiDung()
    {
        return $this->belongsTo(\App\Models\NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}