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
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'attributes' => 'array',
        'average_rating' => 'decimal:2',
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
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('min_quantity');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    public function getDisplayPriceAttribute()
    {
        if ($this->discount_price && $this->discount_price < $this->base_price) {
            return $this->discount_price;
        }
        return $this->base_price;
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_price && $this->discount_price < $this->base_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) return 0;

        $percentage = (($this->base_price - $this->discount_price) / $this->base_price) * 100;
        return round($percentage);
    }

    // Methods
    public function getPriceForQuantity($quantity)
    {
        $price = $this->prices()
            ->where('min_quantity', '<=', $quantity)
            ->where(function ($query) use ($quantity) {
                $query->where('max_quantity', '>=', $quantity)
                    ->orWhereNull('max_quantity');
            })
            ->orderBy('min_quantity', 'desc')
            ->first();

        return $price ? $price->price : $this->display_price;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateRating()
    {
        $reviews = $this->reviews()->where('status', 'approved');
        $averageRating = $reviews->avg('rating');
        $totalReviews = $reviews->count();

        $this->update([
            'average_rating' => $averageRating ?? 0,
            'total_reviews' => $totalReviews,
        ]);
    }
}
