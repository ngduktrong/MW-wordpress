<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuatChieu extends Model
{
    protected $table = 'suatchieu';      // tên bảng trong DB
    protected $primaryKey = 'MaSuatChieu';
    public $timestamps = false;          // nếu bảng không có created_at, updated_at

    protected $fillable = [
        'MaPhim',
        'MaPhong',
        'NgayGioChieu'
    ];

    // ✅ Accessor để format ngày giờ chiếu
    public function getNgayGioChieuFormattedAttribute()
    {
        if (!$this->NgayGioChieu) {
            return null;
        }
        return date('d-m-Y H:i', strtotime($this->NgayGioChieu));
    }

    // ✅ Quan hệ: Suất chiếu thuộc về 1 phim
    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }

    // ✅ Quan hệ: Suất chiếu thuộc về 1 phòng
    public function phong()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }
}
