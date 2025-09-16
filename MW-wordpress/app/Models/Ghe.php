<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ghe extends Model
{
    use HasFactory;

    protected $table = 'Ghe';
    public $timestamps = false;
    public $incrementing = false; // không auto-increment
    protected $primaryKey = null; // Laravel mặc định yêu cầu 1 key, mình override lại

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
     * (dùng query thủ công vì Eloquent không hỗ trợ composite key natively)
     */
    public function ves()
    {
        return Ve::where('MaPhong', $this->MaPhong)
                 ->where('SoGhe', $this->SoGhe);
    }

    /**
     * Override để khi update/save thì Laravel biết
     * dùng MaPhong + SoGhe làm điều kiện
     */
    protected function setKeysForSaveQuery($query)
    {
        $query->where('MaPhong', '=', $this->MaPhong)
              ->where('SoGhe', '=', $this->SoGhe);

        return $query;
    }
}
