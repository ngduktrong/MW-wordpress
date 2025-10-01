<?php
// app/Models/HoaDon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoaDon extends Model
{
    protected $table = 'HoaDon';
    protected $primaryKey = 'MaHoaDon';
    public $incrementing = true;
    protected $keyType = 'int';

    // Schema dùng cột NgayLap DEFAULT CURRENT_TIMESTAMP, nên tắt timestamps của Eloquent
    public $timestamps = false;

    protected $fillable = [
        'MaNhanVien',
        'MaKhachHang',
        'NgayLap',
        'TongTien',
    ];

    protected $casts = [
        'MaNhanVien' => 'integer',
        'MaKhachHang' => 'integer',
        'NgayLap' => 'datetime',
        'TongTien' => 'decimal:2',
    ];

    /**
     * Nhân viên lập hóa đơn (nullable)
     * FK: HoaDon.MaNhanVien -> NhanVien.MaNguoiDung
     */
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'MaNhanVien', 'MaNguoiDung');
    }

    /**
     * Khách hàng (nullable)
     * FK: HoaDon.MaKhachHang -> KhachHang.MaNguoiDung
     */
    public function khachHang(): BelongsTo
    {
        return $this->belongsTo(KhachHang::class, 'MaKhachHang', 'MaNguoiDung');
    }

    /**
     * Các vé liên quan tới hóa đơn này
     * FK: Ve.MaHoaDon -> HoaDon.MaHoaDon
     */
    public function ves(): HasMany
    {
        return $this->hasMany(Ve::class, 'MaHoaDon', 'MaHoaDon');
    }
}
