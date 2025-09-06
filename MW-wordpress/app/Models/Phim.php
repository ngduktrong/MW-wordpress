<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    // Khai báo tên bảng nếu khác với mặc định (mặc định là "phims")
    protected $table = 'phim';
    protected $primaryKey = 'MaPhim';
    public $timestamps = false; // Nếu bảng không có created_at, updated_at

    // Cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'TenPhim',
        'ThoiLuong',
        'NgayKhoiChieu',
        'NuocSanXuat',
        'DinhDang',
        'MoTa',
        'DaoDien',
        'DuongDanPoster'
    ];

    // Ví dụ accessor: định dạng ngày khởi chiếu
    public function getNgayKhoiChieuFormattedAttribute()
    {
        if (!$this->NgayKhoiChieu) {
            return null;
        }
        return date('d-m-Y', strtotime($this->NgayKhoiChieu));
    }
}
