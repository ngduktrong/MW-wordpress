<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'KhachHang';
    protected $primaryKey = 'MaNguoiDung';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaNguoiDung',
        'DiemTichLuy'
    ];

    protected $casts = [
        'DiemTichLuy' => 'integer'
    ];

    /**
     * Mối quan hệ với bảng NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mối quan hệ với bảng HoaDon
     */
    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'MaKhachHang', 'MaNguoiDung');
    }

    /**
     * Tăng điểm tích lũy cho khách hàng
     */
    public function tangDiemTichLuy($diem)
    {
        $this->DiemTichLuy += $diem;
        return $this->save();
    }

    /**
     * Giảm điểm tích lũy cho khách hàng
     */
    public function giamDiemTichLuy($diem)
    {
        $this->DiemTichLuy = max(0, $this->DiemTichLuy - $diem);
        return $this->save();
    }

    /**
     * Scope để lọc khách hàng có điểm tích lũy tối thiểu
     */
    public function scopeCoDiemTichLuy($query, $diemToiThieu)
    {
        return $query->where('DiemTichLuy', '>=', $diemToiThieu);
    }
}