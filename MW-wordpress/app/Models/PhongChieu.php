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
    public $incrementing = true;

    protected $fillable = [
        'TenPhong',
        'SoLuongGhe',
        'LoaiPhong'
    ];

    protected $casts = [
        'MaPhong' => 'integer',
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

    /**
     * Mối quan hệ với bảng Ve thông qua SuatChieu
     */
    public function ves()
    {
        return $this->hasManyThrough(Ve::class, SuatChieu::class, 'MaPhong', 'MaSuatChieu', 'MaPhong', 'MaSuatChieu');
    }
}