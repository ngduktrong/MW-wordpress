<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhimSeeder extends Seeder
{
    public function run()
    {
        DB::table('Phim')->insert([
            [
                'TenPhim' => 'Minions',
                'ThoiLuong' => 90,
                'NgayKhoiChieu' => '2024-01-15',
                'NuocSanXuat' => 'USA',
                'DinhDang' => '3D',
                'MoTa' => 'Phiêu lưu của Minions',
                'DaoDien' => 'Pierre Coffin',
                'DuongDanPoster' => 'poster_minions.jpg',
            ],
            [
                'TenPhim' => 'Doremon Movie',
                'ThoiLuong' => 50,
                'NgayKhoiChieu' => '2025-05-23',
                'NuocSanXuat' => 'JPN',
                'DinhDang' => '5D',
                'MoTa' => 'Cuộc phiêu lưu đến hành tinh khỉ',
                'DaoDien' => 'Puskas',
                'DuongDanPoster' => 'doremon.jpg',
            ],
        ]);
    }
}
