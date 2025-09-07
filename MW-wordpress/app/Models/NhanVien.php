<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends NguoiDung
{
    protected $table = 'nhan_vien';
    protected $primaryKey = 'MaNguoiDung';
    public $incrementing = false; // Vì MaNguoiDung là khóa ngoại từ NguoiDung
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'VaiTro',
        'ChucVu',
        'Luong',
    ];

    // Các vai trò cho nhân viên
    const VAITRO_ADMIN   = 'Admin';
    const VAITRO_QUANLY  = 'QuanLy';
    const VAITRO_THUNGAN = 'ThuNgan';
    const VAITRO_BANVE   = 'BanVe';

    // Quan hệ 1-1: NhanVien thuộc về 1 NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Getter cho vai trò (trả về in hoa chữ cái đầu)
    public function getVaiTroAttribute($value)
    {
        return ucfirst($value);
    }

    // Setter để chuẩn hóa giá trị vai trò
    public function setVaiTroAttribute($value)
    {
        $roles = [
            self::VAITRO_ADMIN,
            self::VAITRO_QUANLY,
            self::VAITRO_THUNGAN,
            self::VAITRO_BANVE
        ];
        $this->attributes['VaiTro'] = in_array($value, $roles) ? $value : self::VAITRO_BANVE;
    }

    // Format lương hiển thị đẹp hơn
    public function getLuongFormattedAttribute()
    {
        return number_format($this->Luong, 0, ',', '.') . ' VND';
    }
}
