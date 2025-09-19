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
    protected $primaryKey = ['MaPhong', 'SoGhe'];

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
    // Add this method to handle composite keys for update:
    protected function setKeysForSaveQuery($query)
    {
    return $query->where([
        'MaPhong' => $this->getAttribute('MaPhong'),
        'SoGhe' => $this->getOriginal('SoGhe') // Use original value for where clause
    ]);
    }
    
}
