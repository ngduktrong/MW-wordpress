<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\Ve;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerVeController extends Controller
{   
    public function index(){
        $user = Auth::user();
        $maNguoiDung = $user->MaNguoiDung;

        $ves = Ve::with(['hoaDon','suatChieu.phim','suatChieu.phongChieu'])->whereHas('hoaDon',function($query)use($maNguoiDung){
            $query->where('MaKhachHang',$maNguoiDung);
        })->orderByDesc('NgayDat')->get();
        return view('CustomerMyTickets',compact('ves'));

    }
    // Hiển thị trang xác nhận vé
    public function confirm()
    {
        $maSuatChieu = session('ma_suat_chieu');
        $chonVePending = session('chon_ve_pending', []);

        if (!$maSuatChieu || empty($chonVePending)) {
            return redirect()->route('home')->with('error', 'Bạn chưa chọn ghế.');
        }

        $suatchieu = SuatChieu::with('phim','phongChieu')->findOrFail($maSuatChieu);

        $dsVe = Ve::whereIn('MaVe', $chonVePending)->get();
        $chonGhe = $dsVe->pluck('SoGhe')->toArray();

        return view('VeConfirm', compact('suatchieu','dsVe','chonGhe'));
    }

    // Xác nhận đặt vé
    public function bookTicket(Request $request)
{
    $maSuatChieu = session('ma_suat_chieu');
    $chonVePending = session('chon_ve_pending', []);

    if (!$maSuatChieu || empty($chonVePending)) {
        return redirect()->route('ve.confirm')->with('error', 'Bạn chưa chọn ghế.');
    }

    DB::beginTransaction();
    try {
        $dsVe = Ve::whereIn('MaVe', $chonVePending)
                   ->where('TrangThai','pending')
                   ->get();

        if($dsVe->isEmpty()){
            DB::rollBack();
            return redirect()->route('ve.confirm')->with('error','Vé pending không tồn tại.');
        }

        $user = Auth::user();
        $khachHang = \App\Models\KhachHang::where('MaNguoiDung', $user->MaNguoiDung)->first();

        if (!$khachHang) {
            DB::rollBack();
            return redirect()->route('ve.confirm')->with('error', 'Không tìm thấy khách hàng trong hệ thống.');
        }

        // ✅ Tạo hóa đơn
        $tongTien = $dsVe->count() * 50000;
        $hoaDon = HoaDon::create([
            'MaKhachHang' => $khachHang->MaNguoiDung,
            'TongTien' => $tongTien,
            'NgayLap' => now(),
        ]);

        // ✅ Cập nhật vé
        foreach($dsVe as $ve){
            $ve->update([
                'MaHoaDon' => $hoaDon->MaHoaDon,
                'TrangThai' => 'paid',
            ]);
        }

        DB::commit();

        session()->forget(['ma_suat_chieu','chon_ve_pending']);

        // ✅ Sau khi đặt thành công → sang trang chi tiết vé
        return redirect()
            ->route('ve.detail', $hoaDon->MaHoaDon)
            ->with('success','🎉 Đặt vé thành công! Cảm ơn bạn đã sử dụng dịch vụ.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('ve.confirm')
                         ->with('error','Có lỗi xảy ra: '.$e->getMessage());
    }
}

    // Chi tiết vé
    public function show($maHoaDon)
    {
        $hoaDon = HoaDon::with(['ves.suatChieu.phim','ves.suatChieu.phongChieu'])->findOrFail($maHoaDon);
        return view('VeDetail', compact('hoaDon'));
    }
}
