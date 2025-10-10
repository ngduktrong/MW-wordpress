<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'NhanVien';

    // Khóa chính
    protected $primaryKey = 'MaNguoiDung';

    // Không tự động tăng (vì là FK từ NguoiDung)
    public $incrementing = false;

    // Kiểu khóa chính
    protected $keyType = 'int';

    // Tắt timestamps (vì bảng không có created_at / updated_at)
    public $timestamps = false;

    // Các cột có thể gán
    protected $fillable = [
        'MaNguoiDung',
        'ChucVu',
        'Luong',
        'VaiTro',
    ];

    // ============================
    // QUAN HỆ (RELATIONSHIPS)
    // ============================

    /**
     * Mỗi nhân viên thuộc về một người dùng.
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mỗi nhân viên có thể lập nhiều hóa đơn.
     */
    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'MaNhanVien', 'MaNguoiDung');
    }
}
