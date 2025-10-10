<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;

    protected $table = 'Phim';
    protected $primaryKey = 'MaPhim';
    public $timestamps = false;

    protected $fillable = [
        'TenPhim',
        'MoTa',
        'TheLoai',
        'ThoiLuong',
        'NgayKhoiChieu',
        'DuongDanPoster',
        'NuocSanXuat',
        'DinhDang',
        'DaoDien',
    ];

    /**
     * Quan hệ: 1 phim có nhiều suất chiếu
     */
    protected $casts = [
        'NgayKhoiChieu' => 'date',
    ];
    public function suatChieu()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhim', 'MaPhim');
    }
}
