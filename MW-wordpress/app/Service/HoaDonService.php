<?php
namespace App\Service;

use App\Models\HoaDon;
class HoaDonService{

    public function createHoaDon($maKhachHang, $maNhanVien = null , $tongTien = 0){
        return HoaDon::create([
            'MaKhachHang' => $maKhachHang,
            'MaNhanVien' => $maNhanVien,
            'NgayLap' => now(),
            'TongTien' => $tongTien
        ]);

    }
}