<?php

namespace App\Http\Controllers\Admin\ManageMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.manage_master.voucher.index')->with('products', $products)->with('sb', 'Voucher');
    }

    public function getall(Request $request)
    {
        $query = Voucher::select(
                'vouchers.id', 
                'vouchers.name', 
                'vouchers.code', 
                'vouchers.percent', 
                'vouchers.status',
                'products.name as product_name'
            )
            ->leftJoin('products', 'vouchers.product_id', '=', 'products.id')
            ->orderBy('vouchers.name', 'ASC')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('code', function (Voucher $voucher) {
                $maskedCode = substr($voucher->code, 0, 2) . str_repeat('***', max(0, strlen($voucher->code) - 3));
                return '
                    <span class="code-display" data-full-code="' . $voucher->code . '">' . $maskedCode . '</span>
                ';
            })
            ->addColumn('status', function (Voucher $voucher) {
                $badgeClass = $voucher->status === 'ACTIVE' 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeClass . '">' . $voucher->status . '</span>';
            })
            ->addColumn('action', function (Voucher $voucher) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $voucher->id . '" class="dropdown-item edit">Edit</a></li>
                        <li><a data-id="' . $voucher->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['code', 'status', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50',
            'percent' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:ACTIVE,NON ACTIVE',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $createdCount = 0;
        foreach ($request->products as $productId) {
            $uniqueCode = $request->code . '_' . $productId;

            if (Voucher::where('code', $uniqueCode)->exists()) {
                continue;
            }

            Voucher::create([
                'name' => $request->name,
                'code' => $uniqueCode,
                'percent' => $request->percent,
                'product_id' => $productId,
                'status' => $request->status,
            ]);

            $product = Product::find($productId);
            if ($product) {
                $originalPrice = $product->price_real ?? $product->price;
                $discountedPrice = $originalPrice * (1 - ($request->percent / 100));
                $product->update([
                    'price_real' => $originalPrice,
                    'price' => round($discountedPrice, 2),
                ]);
            }

            $createdCount++;
        }

        if ($createdCount > 0) {
            return redirect()->back()->with('message', "Data voucher berhasil disimpan untuk {$createdCount} produk dan harga produk telah diperbarui");
        } else {
            return redirect()->back()->with('error', 'Tidak ada voucher baru yang dibuat karena kode sudah ada');
        }
    }

    public function get(Request $request)
    {
        return response()->json(
            Voucher::findOrFail($request->id),
            200
        );
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            return redirect()->back()->with('error', 'ID voucher tidak ditemukan');
        }

        $voucher = Voucher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'percent' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:ACTIVE,NON ACTIVE',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $voucher->update([
            'name' => $request->name,
            'code' => $request->code,
            'percent' => $request->percent,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('message', 'Data voucher berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $voucher = Voucher::findOrFail($request->id);
        $voucher->delete();
        return response()->json(['message' => 'Data voucher berhasil dihapus'], 200);
    }
}