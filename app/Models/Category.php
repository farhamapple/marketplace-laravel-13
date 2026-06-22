<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["name", "slug", "description"];

    protected static function booted(): void
    {
        static::creating(fn (Category $category) => $category->slug ??= Str::slug($category->name));
        static::updating(fn (Category $category) => $category->slug = Str::slug($category->name));
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
