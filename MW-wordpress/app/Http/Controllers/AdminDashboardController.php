<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ve;
use App\Models\HoaDon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Tổng số vé đã thanh toán
        $tongVeDaThanhToan = Ve::where('TrangThai', 'paid')->count();
        
        // Tổng doanh thu từ tất cả hóa đơn
        $tongDoanhThu = HoaDon::sum('TongTien');
        
        // Hoặc nếu bạn muốn tính theo ngày hôm nay:
        $today = now()->format('Y-m-d');
        $tongVeHomNay = Ve::where('TrangThai', 'paid')
                          ->whereDate('NgayDat', $today)
                          ->count();
                          
        $tongDoanhThuHomNay = HoaDon::whereDate('NgayLap', $today)
                                   ->sum('TongTien');

        return view('AdminDashBoard', compact(
            'tongVeDaThanhToan', 
            'tongDoanhThu',
            'tongVeHomNay',
            'tongDoanhThuHomNay'
        ));
    }
}