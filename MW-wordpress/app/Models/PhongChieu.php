<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhongChieu extends Model
{
    protected $table = 'phongchieu';   // tên bảng trong DB
    protected $primaryKey = 'MaPhong';
    public $timestamps = false;        // nếu bảng không có created_at, updated_at

    protected $fillable = [
        'TenPhong',
        'SoLuongGhe',
        'LoaiPhong'
    ];

    // Một phòng có nhiều ghế
    public function ghe()
    {
        return $this->hasMany(Ghe::class, 'MaPhong', 'MaPhong');
    }

    // Một phòng có nhiều suất chiếu
    public function suatchieu()
    {
        return $this->hasMany(SuatChieu::class, 'MaPhong', 'MaPhong');
    }
}
