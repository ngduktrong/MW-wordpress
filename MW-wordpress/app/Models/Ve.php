<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    use HasFactory;

    protected $table = 'Ve';
    protected $primaryKey = 'MaVe';
    public $timestamps = false;

    protected $fillable = [
        'MaSuatChieu',
        'MaPhong',
        'SoGhe',
        'MaHoaDon',
        
        'GiaVe',
        'TrangThai',
        'NgayDat',
    ];

    protected $casts = [
        'NgayDat' => 'datetime',
    ];

    /**
     * Vé thuộc về 1 Suất chiếu
     */
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'MaSuatChieu', 'MaSuatChieu');
    }

    /**
     * Vé thuộc về 1 Hóa đơn (nullable)
     */
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHoaDon', 'MaHoaDon');
    }

    /**
     * Vé thuộc về 1 Phòng chiếu
     */
    public function phongChieu()
{
    return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
}

public function ghe()
{
    return Ghe::where('MaPhong', $this->MaPhong)
              ->where('SoGhe', $this->SoGhe)
              ->first();
}
/**
 * Vé thuộc về 1 Khách hàng
 */


}
