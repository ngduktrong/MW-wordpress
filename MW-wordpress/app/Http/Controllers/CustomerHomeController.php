<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phim;
use Carbon\Carbon;

class CustomerHomeController extends Controller
{
    public function show($id, Request $request)
    {
        $phim = Phim::with('suatChieu.phongChieu')->findOrFail($id);

        // ✅ Lấy danh sách ngày chiếu, chỉ lấy từ hôm nay trở đi
        $ngayChieu = $phim->suatChieu()
            ->selectRaw('DATE(NgayGioChieu) as ngay')
            ->whereDate('NgayGioChieu', '>=', Carbon::today())
            ->distinct()
            ->orderBy('ngay')
            ->pluck('ngay');

        // ✅ Lọc suất chiếu theo ngày, chỉ lấy suất chưa chiếu
        $suatTheoNgay = collect();
        if ($request->has('ngay')) {
            $suatTheoNgay = $phim->suatChieu()
                ->with('phongChieu')
                ->whereDate('NgayGioChieu', $request->ngay)
                ->where('NgayGioChieu', '>=', Carbon::now()) // chỉ lấy suất trong tương lai
                ->orderBy('NgayGioChieu')
                ->get();
        }

        return view('home.show', compact('phim', 'ngayChieu', 'suatTheoNgay'));
    }

    public function index()
    {
        $today = now()->toDateString();

        $phimDangChieu = Phim::where('NgayKhoiChieu', '<=', $today)->get();
        $phimSapChieu = Phim::where('NgayKhoiChieu', '>', $today)->get();

        return view('home.index', compact('phimDangChieu', 'phimSapChieu'));
    }
}
