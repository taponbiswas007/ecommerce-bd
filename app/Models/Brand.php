<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'contact_email',
        'contact_phone',
        'contact_address',
        'country',
        'founded_year',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'view_count',
        'social_links',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'social_links' => 'array',
        'founded_year' => 'integer',
        'view_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope a query to only include active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured brands.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to sort brands by order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get all products for this brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get products count for this brand.
     */
    public function productsCount()
    {
        return $this->hasMany(Product::class)->count();
    }

    /**
     * Get the user who created this brand.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this brand.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Get social links
     */
    public function getSocialLinksAttribute($value)
    {
        $defaultLinks = [
            'facebook' => null,
            'twitter' => null,
            'instagram' => null,
            'youtube' => null,
            'linkedin' => null,
            'pinterest' => null,
        ];

        if ($value) {
            return array_merge($defaultLinks, json_decode($value, true));
        }

        return $defaultLinks;
    }

    /**
     * Search brands
     */
    public static function search($search)
    {
        return static::where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('contact_email', 'like', '%' . $search . '%');
    }
}
