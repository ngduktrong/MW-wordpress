<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        // Nếu chưa đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Nếu role không khớp
        if ($user->LoaiTaiKhoan !== $role) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
