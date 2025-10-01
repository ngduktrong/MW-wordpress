<?php
// app/Models/Ve.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ve extends Model
{
    protected $table = 'Ve';
    protected $primaryKey = 'MaVe';
    public $incrementing = true;
    protected $keyType = 'int';

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
        'MaSuatChieu' => 'integer',
        'MaPhong' => 'integer',
        'MaHoaDon' => 'integer',
        'GiaVe' => 'decimal:2',
        'NgayDat' => 'datetime',
    ];

    /**
     * Hóa đơn (nullable)
     */
    public function hoaDon(): BelongsTo
    {
        return $this->belongsTo(HoaDon::class, 'MaHoaDon', 'MaHoaDon');
    }

    /**
     * Suất chiếu
     */
    public function suatChieu(): BelongsTo
    {
        return $this->belongsTo(SuatChieu::class, 'MaSuatChieu', 'MaSuatChieu');
    }

    /**
     * Phòng chiếu
     */
    public function phongChieu(): BelongsTo
    {
        return $this->belongsTo(PhongChieu::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Lưu ý: bảng Ghe có khóa composite (MaPhong, SoGhe).
     * Eloquent không hỗ trợ relationship với composite PK trực tiếp,
     * nên cung cấp helper để lấy đối tượng Ghe tương ứng.
     *
     * Trả về instance Ghe hoặc null.
     */
    public function ghe()
    {
        return Ghe::where('MaPhong', $this->MaPhong)
                  ->where('SoGhe', $this->SoGhe)
                  ->first();
    }

    /**
     * Scope tiện lợi: theo trạng thái
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('TrangThai', $status);
    }

    /**
     * Scope: vé của một suất chiếu
     */
    public function scopeForSuatChieu($query, $maSuatChieu)
    {
        return $query->where('MaSuatChieu', $maSuatChieu);
    }
}
