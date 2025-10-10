<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Phim;

class CustomerHomeController extends Controller
{
    // public function show($id){
    //     $phim =  Phim::findOrFail($id);
    //     return view('home.show',compact('phim'));
    // }
    public function show($id, Request $request)
{
    $phim = Phim::with('suatChieu.phongChieu')->findOrFail($id);

    // lấy danh sách ngày chiếu distinct
    $ngayChieu = $phim->suatChieu()
                      ->selectRaw('DATE(NgayGioChieu) as ngay')
                      ->distinct()
                      ->pluck('ngay');

    $suatTheoNgay = [];
    if ($request->has('ngay')) {
        $suatTheoNgay = $phim->suatChieu()
                             ->with('phongChieu')
                             ->whereDate('NgayGioChieu', $request->ngay)
                             ->get();
    }

    return view('home.show', compact('phim', 'ngayChieu', 'suatTheoNgay'));
}

    public function index(){
        $today = now()->toDateString();

        $phimDangChieu = Phim::where('NgayKhoiChieu' ,'<=',$today)->get();

        $phimSapChieu = Phim::where('NgayKhoiChieu','>',$today)->get();
        return view('home.index',compact('phimDangChieu','phimSapChieu'));
    }
}
