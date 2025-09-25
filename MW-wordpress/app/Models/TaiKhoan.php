<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Model
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
