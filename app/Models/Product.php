<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAttribute;


/**
 * Get the Hashids-encoded ID for this product.
 */
trait ProductHashidsTrait
{
    public function getHashidAttribute()
    {
        return app('hashids')->encode($this->id);
    }

    public static function findByHashid($hashid)
    {
        $decoded = app('hashids')->decode($hashid);
        if (count($decoded) === 0) {
            return null;
        }
        return self::find($decoded[0]);
    }
}

class Product extends Model
{
    /**
     * Get approved reviews for this product.
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class)
            ->where('status', 'approved')
            ->orderByDesc('created_at');
    }

    use HasFactory, ProductHashidsTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'full_description',
        'category_id',
        'brand_id',
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
        'hide_from_frontend',
        'is_deal',
        'deal_end_at',
        'view_count',
        'sold_count',
        'average_rating',
        'total_reviews',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'hide_from_frontend' => 'boolean',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'is_deal' => 'boolean',
        'deal_end_at' => 'datetime',
    ];

    /**
     * Attribute rows associated with the product.
     */
    public function attributesRows()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Computed attribute pairs aggregated by key.
     */
    public function getAttributePairsAttribute(): array
    {
        return $this->attributesRows
            ->groupBy('key')
            ->map(function ($rows) {
                return $rows->pluck('value')->unique()->implode(', ');
            })
            ->toArray();
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', 1);
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


    // Accessor
    public function getIsDealActiveAttribute()
    {
        return $this->is_deal &&
            (!$this->deal_end_at || $this->deal_end_at->isFuture());
    }

    // Scope
    public function scopeDealOfTheDay($query)
    {
        return $query->where('is_deal', 1)
            ->where(function ($q) {
                $q->whereNull('deal_end_at')
                    ->orWhere('deal_end_at', '>', now());
            });
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

    // Packaging rules: defines shipping units (e.g., Roll = 10 KG, Cartoon = 50 KG)
    public function packagingRules()
    {
        return $this->hasMany(PackagingRule::class)->where('is_active', true)->orderBy('priority', 'desc');
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

    public function isInWishlist()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->wishlists()->where('user_id', Auth::id())->exists();
    }

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * Get the tax override for this product
     */
    public function taxOverride()
    {
        return $this->hasOne(ProductTaxOverride::class);
    }

    /**
     * Get the effective VAT percentage for this product
     */
    public function getEffectiveVatPercentage()
    {
        $override = $this->taxOverride;

        if ($override && $override->isActive()) {
            $vatPercentage = $override->getEffectiveVatPercentage();
            if ($vatPercentage !== null) {
                return $vatPercentage;
            }
        }

        return VatAitSetting::current()->vat_enabled ? VatAitSetting::current()->default_vat_percentage : 0;
    }

    /**
     * Get the effective AIT percentage for this product
     */
    public function getEffectiveAitPercentage()
    {
        $override = $this->taxOverride;

        if ($override && $override->isActive()) {
            $aitPercentage = $override->getEffectiveAitPercentage();
            if ($aitPercentage !== null) {
                return $aitPercentage;
            }
        }

        // Check if category is exempt from AIT
        $settings = VatAitSetting::current();
        if ($this->category && $settings->isCategoryAitExempt($this->category_id)) {
            return 0;
        }

        return $settings->ait_enabled ? $settings->default_ait_percentage : 0;
    }

    /**
     * Check if VAT is included in the price
     */
    public function isVatIncluded()
    {
        $override = $this->taxOverride;

        if ($override && $override->isActive()) {
            $included = $override->getVatIncludedInPrice();
            if ($included !== null) {
                return $included;
            }
        }

        return VatAitSetting::current()->vat_included_in_price;
    }

    /**
     * Check if AIT is included in the price
     */
    public function isAitIncluded()
    {
        $override = $this->taxOverride;

        if ($override && $override->isActive()) {
            $included = $override->getAitIncludedInPrice();
            if ($included !== null) {
                return $included;
            }
        }

        return VatAitSetting::current()->ait_included_in_price;
    }
}
