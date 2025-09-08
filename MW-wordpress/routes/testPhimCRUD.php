<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\CRUD\PhimCRUD;

// Trang hiển thị giao diện CRUD 1 trang
Route::get('/phim-crud', function () {
    return view('phim-crud'); // view ở resources/views/phim-crud.blade.php
});

// API list phim (JSON)
Route::get('/api/phims', fn() => (new PhimCRUD())->index());

// API show 1 phim
Route::get('/api/phims/{id}', fn($id) => (new PhimCRUD())->show($id));

// API create phim
Route::post('/api/phims', function (Request $request) {
    return (new PhimCRUD())->store($request);
});

// API update phim
Route::put('/api/phims/{id}', function (Request $request, $id) {
    return (new PhimCRUD())->update($request, $id);
});

// API delete phim
Route::delete('/api/phims/{id}', fn($id) => (new PhimCRUD())->destroy($id));
