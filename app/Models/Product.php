<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'full_description',
        'category_id',
        'unit_id',
        'base_price',
        'discount_price',
        'stock_quantity',
        'min_order_quantity',
        'video_url',
        'weight',
        'dimensions',
        'is_featured',
        'is_active',
        'view_count',
        'sold_count',
        'average_rating',
        'total_reviews',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'attributes',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'attributes' => 'array',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Accessors
    public function getFeaturedImageAttribute()
    {
        return $this->images()->where('is_featured', true)->first()
            ?: $this->images()->first();
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->base_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->discount_price || $this->discount_price >= $this->base_price) {
            return 0;
        }

        return round((($this->base_price - $this->discount_price) / $this->base_price) * 100);
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getIsOnSaleAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->base_price;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'base_price');
    }
    // In Product.php
    public function prices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('min_quantity');
    }

    public function getPriceForQuantity($quantity)
    {
        return $this->prices()
            ->where('min_quantity', '<=', $quantity)
            ->where(function ($q) use ($quantity) {
                $q->where('max_quantity', '>=', $quantity)
                    ->orWhereNull('max_quantity');
            })
            ->orderBy('min_quantity', 'desc')
            ->first();
    }
}
