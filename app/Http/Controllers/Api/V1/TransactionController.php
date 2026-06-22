<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @tags Transaction History
 */
class TransactionController extends Controller
{
    /**
     * List transactions for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with('product.category')
            ->where('user_id', $request->user()->id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $perPage = min((int) $request->get('per_page', 15), 50);

        $transactions = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => TransactionResource::collection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
            ],
            'links' => [
                'first' => $transactions->url(1),
                'last' => $transactions->url($transactions->lastPage()),
                'prev' => $transactions->previousPageUrl(),
                'next' => $transactions->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get a single transaction detail.
     */
    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json([
                'error' => ['code' => 'FORBIDDEN', 'message' => 'Aksi tidak diizinkan.'],
            ], 403);
        }

        $transaction->load('product.category');

        return response()->json([
            'data' => new TransactionResource($transaction),
        ]);
    }
}
