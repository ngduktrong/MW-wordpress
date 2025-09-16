<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuatChieu extends Model
{
    use HasFactory;

    protected $table = 'SuatChieu';
    protected $primaryKey = 'MaSuatChieu';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'MaPhim',
        'MaPhong',
        'NgayGioChieu'
    ];

    protected $casts = [
        'MaSuatChieu' => 'integer',
        'MaPhim' => 'integer',
        'MaPhong' => 'integer',
        'NgayGioChieu' => 'datetime'
    ];

    /**
     * Mối quan hệ với bảng Phim
     */
    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }

    /**
     * Mối quan hệ với bảng PhongChieu
     */
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Mối quan hệ với bảng Ve
     */
    public function ves()
    {
        return $this->hasMany(Ve::class, 'MaSuatChieu', 'MaSuatChieu');
    }
}