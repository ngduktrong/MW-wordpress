<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ghe extends Model
{
    protected $table = 'ghe';        // tên bảng trong DB
    public $timestamps = false;      // nếu không có created_at, updated_at

    protected $fillable = [
        'MaPhong',
        'SoGhe'
    ];

    // Ghế thuộc về một phòng chiếu
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }
}
