<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = Cart::with("product.category")
            ->where("user_id", Auth::id())
            ->latest()
            ->get();

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return view("customer.cart", compact("items", "total"));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "required|integer|min:1",
        ]);

        $product = Product::findOrFail($validated["product_id"]);

        if ($product->stock < $validated["quantity"]) {
            return back()->withErrors(["quantity" => "Stok tidak mencukupi. Stok tersedia: " . $product->stock]);
        }

        $existing = Cart::where("user_id", Auth::id())
            ->where("product_id", $validated["product_id"])
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $validated["quantity"];
            if ($product->stock < $newQty) {
                return back()->withErrors(["quantity" => "Stok tidak mencukupi. Stok tersedia: " . $product->stock]);
            }
            $existing->update(["quantity" => $newQty]);
        } else {
            Cart::create([
                "user_id" => Auth::id(),
                "product_id" => $validated["product_id"],
                "quantity" => $validated["quantity"],
            ]);
        }

        return redirect()->route("customer.dashboard")->with("success", "Produk ditambahkan ke keranjang.");
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with("success", "Item dihapus dari keranjang.");
    }

    public function checkout(): RedirectResponse
    {
        $items = Cart::with("product")
            ->where("user_id", Auth::id())
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route("customer.cart.index")->withErrors(["cart" => "Keranjang kosong."]);
        }

        foreach ($items as $item) {
            $product = $item->product;

            if ($product->stock < $item->quantity) {
                return redirect()->route("customer.cart.index")->withErrors([
                    "cart" => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                ]);
            }

            $totalPrice = $product->price * $item->quantity;

            Transaction::create([
                "user_id" => Auth::id(),
                "product_id" => $product->id,
                "type" => "sale",
                "quantity" => $item->quantity,
                "total" => $totalPrice,
                "notes" => "Pembelian melalui keranjang",
            ]);

            $product->decrement("stock", $item->quantity);
            $product->increment("sold", $item->quantity);
        }

        Cart::where("user_id", Auth::id())->delete();

        return redirect()->route("customer.transactions.index")->with("success", "Pembayaran berhasil! " . $items->count() . " item telah ditransaksikan.");
    }
}
