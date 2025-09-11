<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    protected $table = 'HoaDon';
    protected $primaryKey = 'MaHoaDon';
    public $timestamps = false;

    protected $fillable = [
        'MaNhanVien',
        'MaKhachHang',
        'NgayLap',
        'TongTien'
    ];

    protected $casts = [
        'NgayLap' => 'datetime',
        'TongTien' => 'decimal:2'
    ];

    /**
     * Mối quan hệ với bảng NhanVien
     */
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'MaNhanVien', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng KhachHang
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKhachHang', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng Ve
     */
    public function ves()
    {
        return $this->hasMany(Ve::class, 'MaHoaDon', 'MaHoaDon');
    }
}