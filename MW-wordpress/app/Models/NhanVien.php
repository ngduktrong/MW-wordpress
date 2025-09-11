<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'NhanVien';
    protected $primaryKey = 'MaNguoiDung';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'ChucVu',
        'Luong',
        'VaiTro'
    ];

    protected $casts = [
        'Luong' => 'decimal:2'
    ];

    /**
     * Mối quan hệ với bảng NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng HoaDon
     */
    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'MaNhanVien', 'MaNguoiDung');
    }

    /**
     * Kiểm tra xem nhân viên có phải là Admin không
     */
    public function isAdmin()
    {
        return $this->VaiTro === 'Admin';
    }

    /**
     * Kiểm tra xem nhân viên có phải là Quản lý không
     */
    public function isQuanLy()
    {
        return $this->VaiTro === 'QuanLy';
    }

    /**
     * Kiểm tra xem nhân viên có phải là Thu ngân không
     */
    public function isThuNgan()
    {
        return $this->VaiTro === 'ThuNgan';
    }

    /**
     * Kiểm tra xem nhân viên có phải là Bán vé không
     */
    public function isBanVe()
    {
        return $this->VaiTro === 'BanVe';
    }

    /**
     * Scope để lọc theo vai trò
     */
    public function scopeVaiTro($query, $vaiTro)
    {
        return $query->where('VaiTro', $vaiTro);
    }
}