<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NhanVien extends Model
{
    // Bảng NhanVien dùng MaNguoiDung làm khóa chính (foreign key đến NguoiDung)
    protected $table = 'NhanVien';
    protected $primaryKey = 'MaNguoiDung';
    public $incrementing = false; // nếu MaNguoiDung không tự tăng
    protected $keyType = 'int'; // đổi sang 'string' nếu mã là chuỗi
    public $timestamps = false;

    // Cho phép gán hàng loạt cho các trường này
    protected $fillable = [
        'MaNguoiDung',
        'ChucVu',
        'Luong',
        'VaiTro'
    ];

    protected $casts = [
        'Luong' => 'float',
        'MaNguoiDung' => 'int',
    ];

    /**
     * Quan hệ tới bảng NguoiDung
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
