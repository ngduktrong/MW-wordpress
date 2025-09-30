<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'KhachHang';
    protected $primaryKey = 'MaNguoiDung';
    public $timestamps = false;

    // Khóa chính không auto-increment (vì lấy từ bảng NguoiDung)
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        // 'MaNguoiDung', // cân nhắc bỏ để tránh mass-assignment của PK
        'DiemTichLuy',
    ];

    protected $casts = [
        'DiemTichLuy' => 'integer',
    ];

    // Quan hệ: KhachHang thuộc về 1 NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // KhachHang có nhiều HoaDon
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaKhachHang', 'MaNguoiDung');
    }
}
