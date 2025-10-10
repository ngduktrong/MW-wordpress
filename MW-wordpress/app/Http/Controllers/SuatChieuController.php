<?php

namespace App\Http\Controllers;

use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\PhongChieu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuatChieuController extends BaseCrudController
{
    protected $model = SuatChieu::class;
    protected $primaryKey = 'MaSuatChieu';

    public function index()
    {
        $suatChieus = parent::index();
        $phims = Phim::all();
        $phongChieus = PhongChieu::all();
        
        $editId = request()->get('edit');
        $suatChieu = null;
        
        if ($editId) {
            $suatChieu = $this->model::find($editId);
        }
        
        return view('AdminSuatChieu', compact('suatChieus', 'phims', 'phongChieus', 'suatChieu'));
    }

    public function store(Request $request)
    {
        $messages = [
            'NgayGioChieu.after_or_equal' => 'Thời gian chiếu không được ở quá khứ.',
            'NgayGioChieu.required' => 'Vui lòng chọn thời gian chiếu.',
            'NgayGioChieu.date' => 'Thời gian chiếu không hợp lệ.',
            'MaPhim.required' => 'Vui lòng chọn phim.',
            'MaPhim.exists' => 'Phim không tồn tại.',
            'MaPhong.required' => 'Vui lòng chọn phòng chiếu.',
            'MaPhong.exists' => 'Phòng chiếu không tồn tại.',
        ];

        $request->validate([
            'MaPhim' => 'required|exists:Phim,MaPhim',
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'NgayGioChieu' => 'required|date|after_or_equal:now'
        ], $messages);

        // Kiểm tra thời gian trước khi điều chỉnh
        $ngayGioChieuInput = Carbon::parse($request->NgayGioChieu);
        if ($ngayGioChieuInput->lt(now())) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['NgayGioChieu' => 'Không thể thêm suất chiếu trong quá khứ.']);
        }

        // Xử lý logic tự động điều chỉnh thời gian
        $ngayGioChieu = $this->adjustShowtime($request);

        // Thông báo nếu thời gian bị điều chỉnh
        $thongBaoDieuChinh = '';
        if (!$ngayGioChieu->equalTo($ngayGioChieuInput)) {
            $thongBaoDieuChinh = 'Thời gian đã được điều chỉnh từ ' . 
                $ngayGioChieuInput->format('d/m/Y H:i') . ' thành ' . 
                $ngayGioChieu->format('d/m/Y H:i') . ' để tránh trùng lịch.';
        }

        // Tạo dữ liệu mới với thời gian đã điều chỉnh
        $data = $request->all();
        $data['NgayGioChieu'] = $ngayGioChieu;

        $result = $this->model::create($data);
        
        $redirect = redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Thêm suất chiếu thành công.');
        
        if ($thongBaoDieuChinh) {
            $redirect->with('info', $thongBaoDieuChinh);
        }
        
        return $redirect;
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'NgayGioChieu.after_or_equal' => 'Thời gian chiếu không được ở quá khứ.',
            'NgayGioChieu.required' => 'Vui lòng chọn thời gian chiếu.',
            'NgayGioChieu.date' => 'Thời gian chiếu không hợp lệ.',
            'MaPhim.required' => 'Vui lòng chọn phim.',
            'MaPhim.exists' => 'Phim không tồn tại.',
            'MaPhong.required' => 'Vui lòng chọn phòng chiếu.',
            'MaPhong.exists' => 'Phòng chiếu không tồn tại.',
        ];

        $request->validate([
            'MaPhim' => 'required|exists:Phim,MaPhim',
            'MaPhong' => 'required|exists:PhongChieu,MaPhong',
            'NgayGioChieu' => 'required|date|after_or_equal:now'
        ], $messages);

        // Kiểm tra thời gian trước khi điều chỉnh
        $ngayGioChieuInput = Carbon::parse($request->NgayGioChieu);
        if ($ngayGioChieuInput->lt(now())) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['NgayGioChieu' => 'Không thể cập nhật suất chiếu thành thời gian trong quá khứ.']);
        }

        // Xử lý logic tự động điều chỉnh thời gian
        $ngayGioChieu = $this->adjustShowtime($request, $id);

        // Thông báo nếu thời gian bị điều chỉnh
        $thongBaoDieuChinh = '';
        if (!$ngayGioChieu->equalTo($ngayGioChieuInput)) {
            $thongBaoDieuChinh = 'Thời gian đã được điều chỉnh từ ' . 
                $ngayGioChieuInput->format('d/m/Y H:i') . ' thành ' . 
                $ngayGioChieu->format('d/m/Y H:i') . ' để tránh trùng lịch.';
        }

        // Cập nhật dữ liệu với thời gian đã điều chỉnh
        $item = $this->model::findOrFail($id);
        $item->update(array_merge($request->all(), ['NgayGioChieu' => $ngayGioChieu]));
        
        $redirect = redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Cập nhật suất chiếu thành công.');
        
        if ($thongBaoDieuChinh) {
            $redirect->with('info', $thongBaoDieuChinh);
        }
        
        return $redirect;
    }

    public function destroy($id)
    {
        $result = parent::destroy($id);
        
        return redirect()->route('admin.suatchieu.index')
                         ->with('success', 'Xóa suất chiếu thành công');
    }

    /**
     * Điều chỉnh thời gian chiếu tự động nếu trùng với suất chiếu khác trong cùng phòng
     */
    private function adjustShowtime(Request $request, $excludeId = null)
    {
        $maPhim = $request->MaPhim;
        $maPhong = $request->MaPhong;
        $ngayGioChieu = Carbon::parse($request->NgayGioChieu);
        
        // Lấy thông tin phim mới để biết thời lượng
        $phimMoi = Phim::find($maPhim);
        $thoiLuongPhimMoi = $phimMoi->ThoiLuong;

        $maxAttempts = 20;
        $attempt = 0;
        
        do {
            $adjusted = false;
            
            // Tìm tất cả suất chiếu trong cùng PHÒNG trong ngày
            $query = SuatChieu::where('MaPhong', $maPhong)
                ->whereDate('NgayGioChieu', $ngayGioChieu->toDateString());

            if ($excludeId) {
                $query->where('MaSuatChieu', '!=', $excludeId);
            }

            $suatChieusTrongNgay = $query->orderBy('NgayGioChieu')->get();

            foreach ($suatChieusTrongNgay as $suatChieu) {
                // Lấy thông tin phim của suất chiếu hiện tại để biết thời lượng
                $phimHienTai = Phim::find($suatChieu->MaPhim);
                $thoiLuongPhimHienTai = $phimHienTai->ThoiLuong;

                $startTime = Carbon::parse($suatChieu->NgayGioChieu);
                $endTime = $startTime->copy()->addMinutes($thoiLuongPhimHienTai);
                
                // Kiểm tra nếu thời gian chiếu mới nằm trong khoảng thời gian chiếu của suất chiếu hiện có
                if ($ngayGioChieu->between($startTime, $endTime)) {
                    // Điều chỉnh thời gian chiếu mới thành thời gian kết thúc + 10 phút
                    $ngayGioChieu = $endTime->copy()->addMinutes(10);
                    $adjusted = true;
                    break;
                }
                
                // Kiểm tra xem suất chiếu mới có "cắt ngang" suất chiếu hiện có không
                $endTimeMoi = $ngayGioChieu->copy()->addMinutes($thoiLuongPhimMoi);
                if ($ngayGioChieu->lt($endTime) && $endTimeMoi->gt($startTime)) {
                    $ngayGioChieu = $endTime->copy()->addMinutes(10);
                    $adjusted = true;
                    break;
                }
            }
            
            $attempt++;
        } while ($adjusted && $attempt < $maxAttempts);

        return $ngayGioChieu;
    }
}