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
use App\Models\Product;
use App\Models\Transaction;

Route::get('/', function () {
    return view('home', [
        'products' => App\Models\Product::with('category')->get(),
        'totalProduk' => App\Models\Product::count(),
        'totalStok' => App\Models\Product::sum('stock'),
        'totalTerjual' => App\Models\Transaction::where('type', 'sale')->sum('quantity'),
        'totalKategori' => App\Models\Category::count(),
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard', [
                'totalProduk' => Product::count(),
                'totalTerjual' => Transaction::where('type', 'sale')->sum('quantity'),
                'sisaProduk' => Product::sum('stock'),
                'jumlahJenis' => Product::with('category')->get()->unique('category_id')->count(),
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
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    });
});
