<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'comment',
        'response',
        'status',
        'is_verified_purchase',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Observer logic to update product's average_rating and total_reviews
    public static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            $review->updateProductReviewStats();
        });
        static::deleted(function ($review) {
            $review->updateProductReviewStats();
        });
    }

    public function updateProductReviewStats()
    {
        $product = $this->product;
        if (!$product) return;
        // Only count approved reviews
        $approved = $product->reviews()->where('status', 'approved')->get(['rating']);
        $product->total_reviews = $approved->count();
        $product->average_rating = $approved->avg('rating') ?: 0;
        $product->save();
    }
}
