<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'is_featured',
        'display_order',
        'alt_text',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->image_url; // You can modify this for thumbnails
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function getFeaturedImageAttribute()
    {
        return $this->images()->where('is_featured', true)->first()
            ?: $this->images()->where('is_primary', true)->first()
            ?: $this->images()->first();
    }
}
