<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;

    // Tên bảng (phù hợp với migration của bạn)
    protected $table = 'phim';

    // Khóa chính đúng như trong migration: "ma_phim"
    protected $primaryKey = 'ma_phim';

    // Nếu primary key là số nguyên tự tăng
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    // Cho phép mass assignment với cả tên cột PascalCase (DB) và snake_case (form/view)
    protected $fillable = [
        // tên DB (PascalCase)
        'TenPhim',
        'ThoiLuong',
        'NgayKhoiChieu',
        'NuocSanXuat',
        'DinhDang',
        'MoTa',
        'DaoDien',
        'DuongDanPoster',
        'TheLoai',

        // tên snake_case để phù hợp với form/view/controller hiện tại
        'ten_phim',
        'thoi_luong',
        'nam_san_xuat',
        'nuoc_san_xuat',
        'dinh_dang',
        'mo_ta',
        'dao_dien',
        'duong_dan_poster',
        'the_loai',
    ];

    protected $casts = [
        'NgayKhoiChieu' => 'date',
        'ThoiLuong' => 'integer'
    ];

    /***************
     * Accessors & Mutators for compatibility
     *
     * Cho phép bạn dùng $phim->ten_phim, $phim->id, $phim->mo_ta... trong view/controller
     ****************/

    // map id -> ma_phim (để view dùng $p->id vẫn ok)
    public function getIdAttribute()
    {
        return $this->attributes[$this->primaryKey] ?? null;
    }

    // TEN PHIM
    public function getTenPhimAttribute()
    {
        return $this->attributes['TenPhim'] ?? ($this->attributes['ten_phim'] ?? null);
    }
    public function setTenPhimAttribute($value)
    {
        $this->attributes['TenPhim'] = $value;
    }

    // MO TA
    public function getMoTaAttribute()
    {
        return $this->attributes['MoTa'] ?? ($this->attributes['mo_ta'] ?? null);
    }
    public function setMoTaAttribute($value)
    {
        $this->attributes['MoTa'] = $value;
    }

    // THOI LUONG
    public function getThoiLuongAttribute()
    {
        return isset($this->attributes['ThoiLuong'])
            ? (int)$this->attributes['ThoiLuong']
            : ($this->attributes['thoi_luong'] ?? null);
    }
    public function setThoiLuongAttribute($value)
    {
        $this->attributes['ThoiLuong'] = $value;
    }

    // NAM SAN XUAT (mapped to NgayKhoiChieu)
    public function getNamSanXuatAttribute()
    {
        if (!empty($this->attributes['NgayKhoiChieu'])) {
            return date('Y', strtotime($this->attributes['NgayKhoiChieu']));
        }
        return $this->attributes['nam_san_xuat'] ?? null;
    }
    public function setNamSanXuatAttribute($value)
    {
        if ($value && is_numeric($value) && strlen($value) == 4) {
            $this->attributes['NgayKhoiChieu'] = $value . '-01-01';
        } else {
            $this->attributes['NgayKhoiChieu'] = $value;
        }
    }

    // NUOC SAN XUAT
    public function getNuocSanXuatAttribute()
    {
        return $this->attributes['NuocSanXuat'] ?? ($this->attributes['nuoc_san_xuat'] ?? null);
    }
    public function setNuocSanXuatAttribute($value)
    {
        $this->attributes['NuocSanXuat'] = $value;
    }

    // POSTER
    public function getDuongDanPosterAttribute()
    {
        return $this->attributes['DuongDanPoster'] ?? ($this->attributes['duong_dan_poster'] ?? null);
    }
    public function setDuongDanPosterAttribute($value)
    {
        $this->attributes['DuongDanPoster'] = $value;
    }

    // THE LOAI
    public function getTheLoaiAttribute()
    {
        return $this->attributes['TheLoai'] ?? ($this->attributes['the_loai'] ?? null);
    }
    public function setTheLoaiAttribute($value)
    {
        $this->attributes['TheLoai'] = $value;
    }
}
