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
        'NgayDat'
    ];

    protected $casts = [
        'GiaVe' => 'decimal:2',
        'NgayDat' => 'datetime'
    ];

    /**
     * Mối quan hệ với bảng SuatChieu
     */
    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'MaSuatChieu', 'MaSuatChieu');
    }

    /**
     * Mối quan hệ với bảng HoaDon
     */
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHoaDon', 'MaHoaDon');
    }

    /**
     * Mối quan hệ với bảng Ghe (composite key)
     */
    public function ghe()
    {
        // Sử dụng where clause để xử lý composite key
        return $this->hasOne(Ghe::class, 'MaPhong', 'MaPhong')
                    ->where('SoGhe', $this->SoGhe);
    }

    /**
     * Mối quan hệ với bảng PhongChieu
     */
    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Scope để lấy vé theo trạng thái
     */
    public function scopeTrangThai($query, $trangThai)
    {
        return $query->where('TrangThai', $trangThai);
    }

    /**
     * Scope để lấy vé theo suất chiếu
     */
    public function scopeTheoSuatChieu($query, $maSuatChieu)
    {
        return $query->where('MaSuatChieu', $maSuatChieu);
    }
}