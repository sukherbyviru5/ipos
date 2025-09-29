<?php

namespace App\Http\Controllers\Admin\ManageMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\PhotoProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('admin.manage_master.products.index')->with([
            'sb' => 'Product',
            'categories' => $categories
        ]);
    }

    public function getall(Request $request)
    {
        $query = Product::with(['category', 'photos'])
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category_name', function (Product $product) {
                return $product->category ? $product->category->name : '<span class="text-muted">No Category</span>';
            })
            ->addColumn('photos_preview', function (Product $product) {
                $firstPhoto = $product->photos->first();
                if ($firstPhoto) {
                    return '<img src="' . asset($firstPhoto->foto) . '" width="50" class="img-thumbnail">';
                }
                return '<span class="text-muted">No Photo</span>';
            })
            ->addColumn('action', function (Product $product) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $product->id . '" class="dropdown-item edit">Edit</a></li>
                        <li><a data-id="' . $product->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['category_name', 'photos_preview', 'action'])
            ->make(true);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'raw_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'neto' => 'nullable|string',
            'pieces' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->raw_price,
            'stock' => $request->stock,
            'neto' => $request->neto,
            'pieces' => $request->pieces,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/product/' . $filename;
                $file->move(public_path('assets/product'), $filename);
                PhotoProduct::create([
                    'foto' => $path,
                    'id_product' => $product->id,
                ]);
            }
        }

        return redirect()->back()->with('message', 'Data produk berhasil disimpan');
    }

    public function get(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->photos = $product->photos()->get(['id', 'foto']);
        return response()->json($product, 200);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'ID produk tidak ditemukan');
        }
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'raw_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'neto' => 'nullable|string',
            'pieces' => 'nullable|string',
            'deleted_photos' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)
                    ->where('id', '!=', $id)
                    ->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'price' => $request->raw_price,
            'stock' => $request->stock,
            'neto' => $request->neto,
            'pieces' => $request->pieces,
        ]);

        // Handle hapus foto lama
        if ($request->has('deleted_photos') && !empty($request->deleted_photos)) {
            $deletedPhotoIds = explode(',', $request->deleted_photos);
            foreach ($deletedPhotoIds as $photoId) {
                $photo = PhotoProduct::find($photoId);
                if ($photo && $photo->id_product == $id) {
                    if (file_exists(public_path($photo->foto))) {
                        unlink(public_path($photo->foto));
                    }
                    $photo->delete();
                }
            }
        }

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/product/' . $filename;
                $file->move(public_path('assets/product'), $filename);
                PhotoProduct::create([
                    'foto' => $path,
                    'id_product' => $id,
                ]);
            }
        }

        return redirect()->back()->with('message', 'Data produk berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->id);
        
        foreach ($product->photos as $photo) {
            if (file_exists(public_path($photo->foto))) {
                unlink(public_path($photo->foto));
            }
            $photo->delete();
        }
        
        $product->delete();
        return response()->json(['message' => 'Data produk berhasil dihapus'], 200);
    }
}