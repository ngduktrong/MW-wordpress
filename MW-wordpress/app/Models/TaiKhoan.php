<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'TaiKhoan';

    // primary key là TenDangNhap (string)
    protected $primaryKey = 'TenDangNhap';
    public $incrementing = false;
    protected $keyType = 'string';

    // fillable fields
    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'LoaiTaiKhoan',
        'MaNguoiDung',
    ];

    // set timestamps true since migration added timestamps
    public $timestamps = true;

    // BỎ ẨN MẬT KHẨU để hiển thị trong admin (chỉ dùng cho mục đích quản trị)
    // protected $hidden = [
    //     'MatKhau',
    // ];

    /**
     * Relation to NguoiDung (assuming model App\Models\NguoiDung with PK MaNguoiDung)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }

    /**
     * Mutator: LƯU MẬT KHẨU DẠNG VĂN BẢN THÔNG THƯỜNG (theo yêu cầu)
     * CẢNH BÁO: Đây chỉ nên dùng cho mục đích demo/quản trị nội bộ
     */
    public function setMatKhauAttribute($value)
    {
        if (empty($value)) {
            return;
        }
        
        // Lưu mật khẩu dạng văn bản thường (không hash)
        $this->attributes['MatKhau'] = $value;
    }

    /**
     * Accessor: Trả về mật khẩu dạng văn bản
     */
    public function getMatKhauAttribute($value)
    {
        return $value; // Trả về trực tiếp (không mã hóa)
    }
}