<?php

namespace App\Http\Controllers;

use App\Models\SuatChieu;
use App\Models\Ve;
use App\Models\KhachHang;
use App\Models\Ghe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerGheController extends Controller
{
    // Hiển thị sơ đồ ghế
    public function index($maSuatChieu)
    {
        $suatchieu = SuatChieu::with('phongChieu', 'phim')->findOrFail($maSuatChieu);

        $vedat = Ve::where('MaSuatChieu', $maSuatChieu)
                    ->pluck('SoGhe')
                    ->toArray();

        $danhSachGhe = Ghe::where('MaPhong', $suatchieu->MaPhong)
            ->orderByRaw("LEFT(SoGhe,1), CAST(SUBSTRING(SoGhe,2) AS UNSIGNED)")
            ->pluck('SoGhe')
            ->toArray();

        return view('GheIndex', compact('suatchieu', 'vedat', 'danhSachGhe'));
    }

    // Chọn ghế và tạo vé pending
    public function chonGhe(Request $request, $maSuatChieu)
    {
        $chonGhe = $request->input('chon_ghe', []);
        if (empty($chonGhe)) {
            return redirect()->back()->with('error','Bạn chưa chọn ghế.');
        }

        $suatchieu = SuatChieu::findOrFail($maSuatChieu);
        $danhSachGhe = Ghe::where('MaPhong', $suatchieu->MaPhong)->pluck('SoGhe')->toArray();

        DB::beginTransaction();
        try {
            $dsVePending = [];
            foreach($chonGhe as $ghe) {
                $ghe = strtoupper(substr($ghe,0,1)) . str_pad(substr($ghe,1),2,'0',STR_PAD_LEFT);

                if(!in_array($ghe,$danhSachGhe)) {
                    DB::rollBack();
                    return redirect()->back()->with('error',"Ghế $ghe không tồn tại.");
                }

                $existsVe = Ve::where('MaSuatChieu',$maSuatChieu)
                               ->where('MaPhong',$suatchieu->MaPhong)
                               ->where('SoGhe',$ghe)
                               ->exists();
                if($existsVe){
                    DB::rollBack();
                    return redirect()->back()->with('error',"Ghế $ghe đã được chọn.");
                }

                $ve = Ve::create([
                    'MaSuatChieu' => $maSuatChieu,
                    'MaPhong' => $suatchieu->MaPhong,
                    'SoGhe' => $ghe,
                    'GiaVe' => 50000,
                    'TrangThai' => 'pending',
                    'NgayDat' => now(),
                    'MaHoaDon' => null,
                ]);

                $dsVePending[] = $ve->MaVe;
            }

            DB::commit();

            session([
                'ma_suat_chieu' => $maSuatChieu,
                'chon_ve_pending' => $dsVePending
            ]);

            return redirect()->route('ve.confirm');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error','Có lỗi xảy ra: '.$e->getMessage());
        }
    }
}
