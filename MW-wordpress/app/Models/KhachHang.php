<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends NguoiDung
{
    protected $table = 'khach_hang';
    protected $primaryKey = 'MaNguoiDung';
    public $incrementing = false; // vì MaNguoiDung là FK từ bảng nguoi_dung
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'DiemTichLuy',
    ];

    // Quan hệ 1-1: KhachHang thuộc về 1 NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    // Getter để tránh null
    public function getDiemTichLuyAttribute($value)
    {
        return $value ?? 0;
    }

    // Cộng điểm tích lũy
    public function congDiem($soDiem)
    {
        $this->DiemTichLuy += $soDiem;
        $this->save();
    }

    // Trừ điểm tích lũy (có kiểm tra không âm)
    public function truDiem($soDiem)
    {
        $this->DiemTichLuy = max(0, $this->DiemTichLuy - $soDiem);
        $this->save();
    }
}
