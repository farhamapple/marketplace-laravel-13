<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCartRequest;
use App\Http\Resources\V1\CartResource;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @tags Cart & Checkout
 */
class CartController extends Controller
{
    /**
     * List cart items for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $items = Cart::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return response()->json([
            'data' => CartResource::collection($items),
            'meta' => [
                'total_items' => $items->count(),
                'total_price' => (float) $total,
            ],
        ]);
    }

    /**
     * Add a product to cart.
     */
    public function store(StoreCartRequest $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'error' => [
                    'code' => 'INSUFFICIENT_STOCK',
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock,
                ],
            ], 422);
        }

        $existing = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $request->quantity;
            if ($product->stock < $newQty) {
                return response()->json([
                    'error' => [
                        'code' => 'INSUFFICIENT_STOCK',
                        'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock,
                    ],
                ], 422);
            }
            $existing->update(['quantity' => $newQty]);
            $cart = $existing;
        } else {
            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cart->load('product.category');

        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Produk ditambahkan ke keranjang.',
        ], 201);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart): JsonResponse
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json([
                'error' => ['code' => 'FORBIDDEN', 'message' => 'Aksi tidak diizinkan.'],
            ], 403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = $cart->product;

        if ($product->stock < $request->quantity) {
            return response()->json([
                'error' => [
                    'code' => 'INSUFFICIENT_STOCK',
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock,
                ],
            ], 422);
        }

        $cart->update(['quantity' => $request->quantity]);
        $cart->load('product.category');

        return response()->json([
            'data' => new CartResource($cart),
            'message' => 'Jumlah item berhasil diperbarui.',
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Request $request, Cart $cart): JsonResponse
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json([
                'error' => ['code' => 'FORBIDDEN', 'message' => 'Aksi tidak diizinkan.'],
            ], 403);
        }

        $cart->delete();

        return response()->json(null, 204);
    }

    /**
     * Checkout: convert all cart items to transactions.
     */
    public function checkout(Request $request): JsonResponse
    {
        $items = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'error' => ['code' => 'EMPTY_CART', 'message' => 'Keranjang kosong.'],
            ], 422);
        }

        $errors = [];

        foreach ($items as $item) {
            $product = $item->product;

            if ($product->stock < $item->quantity) {
                $errors[] = "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.";
            }
        }

        if (! empty($errors)) {
            return response()->json([
                'error' => [
                    'code' => 'INSUFFICIENT_STOCK',
                    'message' => 'Beberapa produk tidak dapat diproses.',
                    'details' => $errors,
                ],
            ], 422);
        }

        $transactions = [];

        foreach ($items as $item) {
            $product = $item->product;
            $totalPrice = $product->price * $item->quantity;

            $tx = Transaction::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'type' => 'sale',
                'quantity' => $item->quantity,
                'total' => $totalPrice,
                'notes' => 'Pembelian melalui keranjang',
            ]);

            $product->decrement('stock', $item->quantity);
            $product->increment('sold', $item->quantity);

            $transactions[] = $tx;
        }

        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'data' => TransactionResource::collection(collect($transactions)),
            'message' => 'Pembayaran berhasil! ' . count($transactions) . ' item telah ditransaksikan.',
        ], 201);
    }
}
