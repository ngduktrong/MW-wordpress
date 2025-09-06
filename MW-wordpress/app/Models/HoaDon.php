<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoadon';          // tên bảng trong DB
    protected $primaryKey = 'MaHoaDon';
    public $timestamps = false;           // nếu không có created_at, updated_at

    protected $fillable = [
        'MaNhanVien',
        'MaKhachHang',
        'NgayLap',
        'TongTien'
    ];

    // Một hóa đơn thuộc về một nhân viên
    public function nhanVien()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNhanVien', 'MaNguoiDung');
    }

    // Một hóa đơn thuộc về một khách hàng
    public function khachHang()
    {
        return $this->belongsTo(NguoiDung::class, 'MaKhachHang', 'MaNguoiDung');
    }

    // Một hóa đơn có nhiều vé
    public function ve()
    {
        return $this->hasMany(Ve::class, 'MaHoaDon', 'MaHoaDon');
    }
}
