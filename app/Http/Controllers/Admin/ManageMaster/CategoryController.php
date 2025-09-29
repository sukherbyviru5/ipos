<?php

namespace App\Http\Controllers\Admin\ManageMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.manage_master.categories.index')->with('sb', 'Category');
    }

    public function getall(Request $request)
    {
        $query = Category::select('id', 'name', 'slug', 'description')
                ->orderBy('name', 'ASC')
                ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Category $category) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $category->id . '" class="dropdown-item edit">Edit</a></li>
                        <li><a data-id="' . $category->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('message', 'Data kategori berhasil disimpan');
    }

    public function get(Request $request)
    {
        return response()->json(
            Category::findOrFail($request->id),
            200
        );
    }


    public function update(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'ID kategori tidak ditemukan');
        }

        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $slug = Str::slug($request->name);
        $count = Category::where('slug', $slug)
                    ->where('id', '!=', $id) 
                    ->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('message', 'Data kategori berhasil diupdate');
    }


    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->delete();
        return response()->json(['message' => 'Data kategori berhasil dihapus'], 200);
    }
}