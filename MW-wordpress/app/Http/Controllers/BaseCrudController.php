<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseCrudController extends Controller
{
    
    protected $model;

    
    protected $primaryKey = 'id';

    
    public function index()
    {
        return $this->model::all();
    }

    
    public function store(Request $request)
    {
        $item = $this->model::create($request->all());
        return $item;
    }

    
    public function show($id)
    {
        return $this->model::findOrFail($id);
    }

   
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }
}
