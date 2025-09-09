<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Phim extends Model
{
    // Tên bảng chính xác theo schema
    protected $table = 'Phim';

    // Khóa chính
    protected $primaryKey = 'MaPhim';
    public $incrementing = true;
    protected $keyType = 'int';

    // Bảng không có created_at/updated_at
    public $timestamps = false;

    // Gán hàng loạt
    protected $fillable = [
        'TenPhim',
        'ThoiLuong',
        'NgayKhoiChieu',
        'NuocSanXuat',
        'DinhDang',
        'MoTa',
        'DaoDien',
        'DuongDanPoster',
    ];

    // Casts
    protected $casts = [
        'ThoiLuong' => 'integer',
        'NgayKhoiChieu' => 'date',
    ];

    // Accessor: $phim->ngay_khoi_chieu_formatted
    public function getNgayKhoiChieuFormattedAttribute()
    {
        if (empty($this->NgayKhoiChieu)) {
            return null;
        }

        if ($this->NgayKhoiChieu instanceof Carbon) {
            return $this->NgayKhoiChieu->format('d-m-Y');
        }

        return Carbon::parse($this->NgayKhoiChieu)->format('d-m-Y');
    }

    // Mutator: chuẩn hoá ngày khi set
    public function setNgayKhoiChieuAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['NgayKhoiChieu'] = null;
            return;
        }

        $dt = Carbon::parse($value);
        $this->attributes['NgayKhoiChieu'] = $dt->format('Y-m-d');
    }
}
