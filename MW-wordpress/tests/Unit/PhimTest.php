<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PhimTest extends TestCase
{
    /**
     * Kiểm tra bảng Phim có ít nhất 1 bản ghi.
     */
    public function test_phim_table_has_at_least_one_row()
    {
        $cnt = DB::table('Phim')->count();
        $this->assertGreaterThan(0, $cnt, "Bảng Phim phải có ít nhất 1 hàng. Hiện: {$cnt}");
    }

    /**
     * Kiểm tra mỗi hàng phim có các trường bắt buộc hợp lệ.
     */
    public function test_each_phim_row_has_required_fields()
    {
        $rows = DB::table('Phim')->get();

        $this->assertNotEmpty($rows, 'Không có dòng nào trong bảng Phim để kiểm tra.');

        foreach ($rows as $r) {
            $this->assertNotEmpty($r->TenPhim ?? null, "TenPhim rỗng (MaPhim={$r->MaPhim})");
            $this->assertTrue(isset($r->ThoiLuong) && (int)$r->ThoiLuong > 0, "ThoiLuong phải > 0 (MaPhim={$r->MaPhim})");
            $this->assertNotEmpty($r->NgayKhoiChieu ?? null, "NgayKhoiChieu rỗng (MaPhim={$r->MaPhim})");
            // kiểm tra định dạng ngày cơ bản
            $d = date_create($r->NgayKhoiChieu ?? '');
            $this->assertTrue($d !== false, "NgayKhoiChieu không hợp lệ (MaPhim={$r->MaPhim})");
            $this->assertNotEmpty($r->NuocSanXuat ?? null, "NuocSanXuat rỗng (MaPhim={$r->MaPhim})");
            $this->assertNotEmpty($r->DinhDang ?? null, "DinhDang rỗng (MaPhim={$r->MaPhim})");
            $this->assertNotEmpty($r->DaoDien ?? null, "DaoDien rỗng (MaPhim={$r->MaPhim})");
            $this->assertLessThan(1000, (int)$r->ThoiLuong, "ThoiLuong quá lớn (MaPhim={$r->MaPhim})");
        }
    }

    /**
     * Kiểm tra có tồn tại 2 phim mẫu Minions và Doremon Movie (nếu bạn muốn bắt buộc).
     */
    public function test_contains_minions_and_doremon_movie()
    {
        $names = DB::table('Phim')->pluck('TenPhim')->map(function($v){ return trim($v); })->toArray();
        $this->assertContains('Minions', $names, "Không tìm thấy 'Minions' trong bảng Phim.");
        $this->assertContains('Doremon Movie', $names, "Không tìm thấy 'Doremon Movie' trong bảng Phim.");
    }
}
