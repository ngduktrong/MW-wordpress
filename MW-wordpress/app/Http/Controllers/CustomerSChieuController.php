<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\PhongChieu;

class CustomerSChieuController extends Controller
{
    public function chonPhong($id){
        $phim = Phim::findOrFail($id);
        $phongs = PhongChieu::whereHas('suatchieu' , function($q) use($id){
            $q->where('MaPhim', $id);
    })->get();
    return view('suatchieu.chonphong',compact('phim','phongs'));

    
    }
    public function chonSuat($id,$maPhong){
        $phim = Phim::findOrFail($id);
        $phong   = PhongChieu::findOrFail($maPhong);
        $suatchieu = SuatChieu::where('MaPhim',$id)->where('MaPhong',$maPhong)->get();
        return view('suatchieu.chonsuat', compact('phim', 'phong', 'suatchieu'));
    }
}
