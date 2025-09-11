<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

/**
 * BaseCrudController (cha chung - dùng cho nhiều resource)
 * - Đặt 1 file controller cha, các controller con kế thừa.
 * - Controller con chỉ cần khai báo: $modelClass, $resource, $validationRules (nếu cần)
 */
abstract class BaseCrudController extends BaseController
{
    /**
     * Model class full name, ví dụ: \App\Models\Phim::class
     * @var string
     */
    protected $modelClass;

    /**
     * Resource name (folder view + route name), ví dụ 'phim'
     * @var string
     */
    protected $resource;

    /**
     * Validation rules mặc định dùng cho store/update. Controller con override nếu cần.
     * @var array
     */
    protected $validationRules = [];

    public function __construct()
    {
        if (empty($this->resource) && !empty($this->modelClass)) {
            $parts = explode('\\', $this->modelClass);
            $this->resource = strtolower(end($parts));
        }
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $items = ($this->modelClass)::paginate($perPage);

        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view("{$this->resource}.index", compact('items'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'send POST to store']);
        }

        return view("{$this->resource}.create");
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (!empty($this->validationRules)) {
            $v = Validator::make($data, $this->validationRules);
            if ($v->fails()) {
                return redirect()->back()->withErrors($v)->withInput();
            }
        }

        $item = ($this->modelClass)::create($data);

        return redirect()->route("{$this->resource}.index")->with('success', 'Created successfully');
    }

    public function show(Request $request, $id)
    {
        $item = ($this->modelClass)::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return view("{$this->resource}.show", compact('item'));
    }

    public function edit(Request $request, $id)
    {
        $item = ($this->modelClass)::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return view("{$this->resource}.edit", compact('item'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = ($this->modelClass)::findOrFail($id);

        if (!empty($this->validationRules)) {
            $v = Validator::make($data, $this->validationRules);
            if ($v->fails()) {
                return redirect()->back()->withErrors($v)->withInput();
            }
        }

        $item->update($data);

        return redirect()->route("{$this->resource}.index")->with('success', 'Updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $item = ($this->modelClass)::findOrFail($id);
        $item->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route("{$this->resource}.index")->with('success', 'Deleted successfully');
    }
}
