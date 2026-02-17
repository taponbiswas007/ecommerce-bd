<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DropshippingProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cj_product_id',
        'local_product_id',
        'name',
        'description',
        'unit_price',
        'selling_price',
        'profit_margin',
        'category',
        'sub_category',
        'image_url',
        'sku',
        'stock',
        'minimum_order_quantity',
        'product_attributes',
        'shipping_info',
        'is_available',
        'is_active',
        'cj_response_data',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'product_attributes' => 'array',
        'shipping_info' => 'array',
        'cj_response_data' => 'array',
    ];

    /**
     * Get all orders containing this dropshipping product
     */
    public function orderItems()
    {
        return $this->hasMany(DropshippingOrderItem::class);
    }

    /**
     * Get related orders
     */
    public function orders()
    {
        return $this->hasManyThrough(
            DropshippingOrder::class,
            DropshippingOrderItem::class,
            'dropshipping_product_id',
            'id',
            'id',
            'dropshipping_order_id'
        );
    }

    public function localProduct()
    {
        return $this->belongsTo(Product::class, 'local_product_id');
    }

    /**
     * Scope to get active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get available products
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }
}
