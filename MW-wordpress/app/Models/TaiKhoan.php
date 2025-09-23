<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Authenticatable
{
    use Notifiable;

    /**
     * Table name
     */
    protected $table = 'TaiKhoan';

    /**
     * Primary key is TenDangNhap (string, not auto-increment)
     */
    protected $primaryKey = 'TenDangNhap';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Migration của bạn không có created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'LoaiTaiKhoan',
        'MaNguoiDung',
    ];

    /**
     * Hide password when serializing to array/json
     */
    protected $hidden = [
        'MatKhau',
    ];

    /**
     * Nếu bạn dùng Auth::attempt() và cột mật khẩu là MatKhau,
     * override getAuthPassword để Laravel biết dùng cột nào:
     */
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    /**
     * Mutator: khi set MatKhau, tự động hash nếu cần
     * Lưu ý: nếu đã là hash (Hash::needsRehash == false) thì giữ nguyên,
     * nếu truyền rỗng/null thì bỏ qua.
     */
    public function setMatKhauAttribute($value)
    {
        if ($value === null || $value === '') {
            return;
        }

        // nếu là mật khẩu plain text, hash nó; nếu đã hash, giữ nguyên
        $this->attributes['MatKhau'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Relation tới bảng NguoiDung (nếu cần)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung', 'MaNguoiDung');
    }
}
