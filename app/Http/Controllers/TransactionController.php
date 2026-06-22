<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['product.category', 'user']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return view('transactions.index', [
            'transactions' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('transactions.create', [
            'products' => Product::with('category')->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);
        $unitPrice = $product->price;
        $totalPrice = $unitPrice * $validated['quantity'];

        Transaction::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'total' => $totalPrice,
            'notes' => $validated['notes'],
        ]);

        if ($validated['type'] === 'sale') {
            $product->decrement('stock', $validated['quantity']);
            $product->increment('sold', $validated['quantity']);
        } else {
            $product->increment('stock', $validated['quantity']);
        }

        return to_route('admin.transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function show(Transaction $transaction): View|\Illuminate\Http\JsonResponse
    {
        $transaction->load(['product.category', 'user']);

        if (request()->wantsJson()) {
            return response()->json($transaction);
        }

        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return to_route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
