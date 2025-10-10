<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongChieu extends Model
{
    use HasFactory;

    protected $table = 'PhongChieu';
    protected $primaryKey = 'MaPhong';
    public $timestamps = false;

    protected $fillable = [
        'TenPhong',
        'SoLuongGhe',
        'LoaiPhong',
    ];

    /**
     * 1 Phòng chiếu có nhiều Suất chiếu
     */
    public function suatChieu()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhong', 'MaPhong');
    }

    /**
     * 1 Phòng chiếu có nhiều Ghế
     */
    public function ghe()
    {
        return $this->hasMany(Ghe::class, 'MaPhong', 'MaPhong');
    }

}
