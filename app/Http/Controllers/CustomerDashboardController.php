<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount("products")->get();
        $products = Product::with("category")->get();

        $totalPembelian = Transaction::where('user_id', Auth::id())->where('type', 'sale')->sum('quantity');
        $totalPengeluaran = Transaction::where('user_id', Auth::id())->where('type', 'sale')->sum('total');
        $totalTransaksi = Transaction::where('user_id', Auth::id())->where('type', 'sale')->count();
        $cartCount = Cart::where('user_id', Auth::id())->count();

        return view("customer.dashboard", compact(
            "categories", "products",
            "totalPembelian", "totalPengeluaran", "totalTransaksi", "cartCount"
        ));
    }
}
