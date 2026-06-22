<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ["user_id", "product_id", "type", "quantity", "total", "notes"];

    protected function casts(): array
    {
        return [
            "total" => "decimal:2",
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
