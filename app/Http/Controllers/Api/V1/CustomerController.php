<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCustomerRequest;
use App\Http\Requests\Api\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): CustomerCollection
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            $direction = str_starts_with($request->sort, '-') ? 'desc' : 'asc';
            $field = ltrim($request->sort, '-');
            if (in_array($field, ['name', 'email', 'created_at'])) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->latest();
        }

        $perPage = min((int) $request->get('per_page', 15), 100);

        return new CustomerCollection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'data' => new CustomerResource($user),
        ], 201);
    }

    public function show(User $customer): JsonResponse
    {
        if ($customer->role !== 'customer') {
            return response()->json([
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Customer tidak ditemukan.'],
            ], 404);
        }

        return response()->json([
            'data' => new CustomerResource($customer),
        ]);
    }

    public function update(UpdateCustomerRequest $request, User $customer): JsonResponse
    {
        if ($customer->role !== 'customer') {
            return response()->json([
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Customer tidak ditemukan.'],
            ], 404);
        }

        $customer->update($request->validated());

        return response()->json([
            'data' => new CustomerResource($customer->fresh()),
        ]);
    }

    public function destroy(User $customer): JsonResponse
    {
        if ($customer->role !== 'customer') {
            return response()->json([
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Customer tidak ditemukan.'],
            ], 404);
        }

        $customer->delete();

        return response()->json(null, 204);
    }
}
