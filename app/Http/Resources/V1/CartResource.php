<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'quantity' => $this->quantity,
            'subtotal' => (float) ($this->relationLoaded('product') ? $this->product->price * $this->quantity : 0),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
