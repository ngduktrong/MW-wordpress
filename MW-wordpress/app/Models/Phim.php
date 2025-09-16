<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;

    
    protected $table = 'Phim';

    
    protected $primaryKey = 'MaPhim';

    
    public $incrementing = true;
    protected $keyType = 'int';

    
    public $timestamps = false;

    
    protected $fillable = [
        'TenPhim',
        'ThoiLuong',
        'NgayKhoiChieu',
        'NuocSanXuat',
        'DinhDang',
        'MoTa',
        'DaoDien',
        'DuongDanPoster'
    ];

    protected $casts = [
        'NgayKhoiChieu' => 'date',
        'ThoiLuong' => 'integer'
    ];

   
    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhim', 'MaPhim');
    }
}
