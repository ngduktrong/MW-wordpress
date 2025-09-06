<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = 've';             // tên bảng
    protected $primaryKey = 'MaVe';      // khóa chính
    public $timestamps = false;          // nếu không có created_at, updated_at

    protected $fillable = [
        'MaSuatChieu',
        'MaPhong',
        'SoGhe',
        'MaHoaDon',
        'GiaVe',
        'TrangThai',
        'NgayDat',
        'NgayGioChieu'
    ];

    // ===== Quan hệ Eloquent =====

    // Vé thuộc về 1 suất chiếu
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'MaSuatChieu', 'MaSuatChieu');
    }

    // Vé thuộc về 1 phòng chiếu
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }

    // Vé thuộc về 1 hóa đơn
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHoaDon', 'MaHoaDon');
    }

    // Format Ngày Giờ Chiếu
    public function getNgayGioChieuFormattedAttribute()
    {
        if (!$this->NgayGioChieu) return '';
        return \Carbon\Carbon::parse($this->NgayGioChieu)->format('d-m-Y H:i');
    }
}
