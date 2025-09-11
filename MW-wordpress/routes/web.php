<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PhimController;
Route::resource('phim', PhimController::class);

Route::get('/test-db', function () {
    try {
        $connName = DB::getDefaultConnection();
        $dbName = DB::connection()->getDatabaseName();
        $driver = DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $now = DB::select('SELECT NOW() as now_time')[0]->now_time ?? null;

        // danh sách bảng (SHOW TABLES)
        $tablesRaw = DB::select('SHOW TABLES');
        $tables = array_map(function ($t) { 
            $a = (array)$t; 
            return array_values($a)[0]; 
        }, $tablesRaw);

        // show LIKE checks + information_schema (case-insensitive find)
        $likeUpper = DB::select("SHOW TABLES LIKE 'Phim'");
        $likeLower = DB::select("SHOW TABLES LIKE 'phim'");
        $info = DB::select(
            "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = ? AND LOWER(TABLE_NAME) = ?",
            [$dbName, 'phim']
        );
        $phim_exists = in_array('Phim', $tables, true) ? 'yes' : 'no';
        $phim_exists_lower = in_array('phim', $tables, true) ? 'yes' : 'no';

        return [
            'default_connection' => $connName,
            'database' => $dbName,
            'driver' => $driver,
            'now' => $now,
            'tables_count' => count($tables),
            'tables_sample' => array_slice($tables, 0, 40),
            'Phim_present_exact' => $phim_exists,
            'phim_present_lower' => $phim_exists_lower,
            'SHOW_TABLES_like_Phim' => count($likeUpper),
            'SHOW_TABLES_like_phim' => count($likeLower),
            'information_schema_lookup' => array_map(fn($r)=>(array)$r, $info),
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});
