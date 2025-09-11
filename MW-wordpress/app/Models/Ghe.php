<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ghe extends Model
{
    use HasFactory;

    protected $table = 'Ghe';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'MaPhong',
        'SoGhe'
    ];

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
        return $this->hasMany(Ve::class, ['MaPhong', 'SoGhe'], ['MaPhong', 'SoGhe']);
    }
}