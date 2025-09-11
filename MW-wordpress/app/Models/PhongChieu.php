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
        'LoaiPhong'
    ];

    protected $casts = [
        'SoLuongGhe' => 'integer'
    ];

    /**
     * Mối quan hệ với bảng Ghe
     */
    public function ghes()
    {
        return $this->hasMany(Ghe::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Mối quan hệ với bảng SuatChieu
     */
    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhong', 'MaPhong');
    }
}