<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerTransactionController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

Route::get('/', function (Illuminate\Http\Request $request) {
    $query = App\Models\Product::with('category');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    return view('home', [
        'products' => $query->latest()->paginate(12)->withQueryString(),
        'totalProduk' => App\Models\Product::count(),
        'totalStok' => App\Models\Product::sum('stock'),
        'totalTerjual' => App\Models\Transaction::where('type', 'sale')->sum('quantity'),
        'totalKategori' => App\Models\Category::count(),
    ]);
});

Route::get('/product/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function (Illuminate\Http\Request $request) {
            $query = Product::with('category');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('stock')) {
                match ($request->stock) {
                    'in' => $query->where('stock', '>', 0),
                    'out' => $query->where('stock', 0),
                    'low' => $query->whereBetween('stock', [1, 5]),
                    default => null,
                };
            }

            $lowStockProducts = Product::with('category')->whereBetween('stock', [1, 5])->get();

            return view('dashboard', [
                'products' => $query->latest()->get(),
                'categories' => Category::all(),
                'totalProduk' => Product::count(),
                'totalTerjual' => Transaction::where('type', 'sale')->sum('quantity'),
                'sisaProduk' => Product::sum('stock'),
                'jumlahJenis' => Category::count(),
                'lowStockProducts' => $lowStockProducts,
            ]);
        })->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('transactions', TransactionController::class)->except(['edit', 'update']);
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/transactions', [CustomerTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    });
});
