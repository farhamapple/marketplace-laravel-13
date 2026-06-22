<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerTransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::with("product.category")
            ->where("user_id", Auth::id())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view("customer.transactions", compact("transactions"));
    }
}
