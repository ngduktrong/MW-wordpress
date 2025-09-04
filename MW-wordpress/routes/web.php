<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/test-db', function () {
    try {
        $results = DB::select('SELECT NOW() as now_time');
        return '✅ Kết nối thành công! Giờ trên DB: ' . $results[0]->now_time;
    } catch (\Exception $e) {
        return '❌ Lỗi kết nối: ' . $e->getMessage();
    }
});
