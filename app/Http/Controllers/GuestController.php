<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Category;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    /**
     * Display the home page with a list of products.
     */
    public function home(Request $request)
    {
        $categories = Category::select(['id', 'name', 'slug', 'description'])->get();

        $query = Product::select(['id', 'category_id', 'name', 'slug', 'price', 'stock'])->with([
            'category' => fn($query) => $query->select(['id', 'name', 'slug']),
            'photos' 
        ]);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $products = $query->inRandomOrder()->paginate(12);

        return view('guest.home', compact('categories', 'products'));
    }

    public function showCart()
    {
        $categories = Category::select(['id', 'name', 'slug'])->get();
        return view('guest.cart', compact('categories'));
    }

    public function fetchCart(Request $request)
    {
        $productIds = $request->input('ids', []);
        $products = Product::whereIn('id', $productIds)->get();

        return response()->json($products);
    }

    /**
     * Display a single product's details by slug.
     */
    public function showProduct($slug)
    {
        $product = Product::select(['id', 'category_id', 'name', 'slug', 'price', 'stock', 'neto', 'pieces'])
            ->with([
                'category' => fn($query) => $query->select(['id', 'name', 'slug', 'description']),
                'photos' => fn($query) => $query->select(['id', 'id_product', 'foto']),
            ])
            ->where('slug', $slug)
            ->firstOrFail();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('guest.detail-product', compact('product', 'categories'));
    }

    public function checkout(Request $request)
    {
        $grossAmount = 0;
        $itemDetails = [];
        $discount = 0;
        $voucherCode = null;

        if ($request->has('voucher') && !empty($request->voucher)) {
            $voucher = Voucher::where('code', $request->voucher)->first();
            if ($voucher && $voucher->status === 'ACTIVE') {
                $discount = $voucher->percent;
                $voucherCode = $voucher->code;
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $voucher ? 'Voucher tidak aktif.' : 'Voucher tidak ditemukan.'
                ], 400);
            }
        }

        if ($request->has('items')) {
            $items = $request->items;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $product->price * $quantity;
                $grossAmount += $subtotal;

                $itemDetails[] = [
                    'id' => (string) $product->id,
                    'price' => (int) $product->price,
                    'quantity' => $quantity,
                    'name' => substr($product->name, 0, 50),
                ];
            }
        } else {
            $product = Product::findOrFail($request->product_id);
            $quantity = (int) $request->quantity;
            $grossAmount = $product->price * $quantity;

            $itemDetails[] = [
                'id' => (string) $product->id,
                'price' => (int) $product->price,
                'quantity' => $quantity,
                'name' => substr($product->name, 0, 50),
            ];
            $items = [
                [
                    'id' => $request->product_id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ],
            ];
        }

        $discountAmount = $discount ? ($grossAmount * $discount / 100) : 0;
        $finalAmount = $grossAmount - $discountAmount;

        if ($discountAmount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int)$discountAmount,
                'quantity' => 1,
                'name' => 'Discount ' . $discount . '%',
            ];
        }

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_amount' => $finalAmount,
            'payment_status' => 'pending',
            'delivery_type' => $request->delivery_type ?? 'pickup',
            'delivery_desc' => $request->delivery_desc,
            'voucher_code' => $voucherCode,
            'discount' => $discountAmount,
            'midtrans_order_id' => uniqid('ORDER-'),
        ]);

        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);
            $quantity = (int) $item['quantity'];
            $subtotal = $product->price * $quantity;

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'qty' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->midtrans_order_id,
                'gross_amount' => (int) $finalAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Guest',
                'email' => auth()->user()->email ?? 'guest@example.com',
                'phone' => auth()->user()->phone ?? '08123456789',
            ],
            'custom_field1' => $request->delivery_type ?? '',
            'custom_field2' => $request->delivery_desc ?? '',
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
        ]);
    }

    // Callback dari Midtrans
    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $transactionId = $request->input('transaction_id');
        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if ($transaction) {
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $transaction->update(['payment_status' => 'paid']);
            } elseif ($transactionStatus == 'pending') {
                $transaction->update(['payment_status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $transaction->update(['payment_status' => 'failed']);
            }

            if ($transactionId) {
                $transaction->update([
                    'midtrans_transaction_id' => $transactionId,
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function success(Request $request)
    {
        $orderId = $request->input('order');
        if(!$orderId) {
            abort(404);
        }
        $transaction = Transaction::where('midtrans_order_id', $orderId)->with(['items'])->first();
        if(!$transaction) {
            abort(404);
        }
        $categories = Category::select(['id', 'name', 'slug'])->get();
        return view('guest.checkout-success', compact('categories', 'transaction'));
    }


    /**
     * Cek validitas kode voucher
     */
    public function voucher(Request $request)
    {
        $request->validate([
            'voucher' => 'required|string'
        ]);

        $voucher = Voucher::where('code', $request->voucher)->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode voucher tidak ditemukan.'
            ], 404);
        }

        if ($voucher->status == 'NON ACTIVE') {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher sudah tidak aktif.'
            ], 400);
        }
      
        return response()->json([
            'status' => 'success',
            'message' => 'Voucher valid.',
            'data' => [
                'code' => $voucher->code,
                'discount' => $voucher->percent,
                'name' => $voucher->name,
            ]
        ]);
    }
}
