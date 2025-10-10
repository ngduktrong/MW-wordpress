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

    protected $fillable = [
        'MaPhim',
        'MaPhong',
        'NgayGioChieu'
    ];

    protected $casts = [
        'NgayGioChieu' => 'datetime'
    ];

    /**
     * Suất chiếu thuộc về 1 Phim
     */
    public function phim()
    {
        return $this->belongsTo(Phim::class, 'MaPhim', 'MaPhim');
    }

    /**
     * Suất chiếu diễn ra trong 1 Phòng chiếu
     */
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Suất chiếu có nhiều Vé
     */
    public function ves()
    {
        return $this->hasMany(Ve::class, 'MaSuatChieu', 'MaSuatChieu');
    }
}
