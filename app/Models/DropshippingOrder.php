<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DropshippingOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'cj_order_number',
        'cj_order_status',
        'cost_price',
        'selling_price',
        'profit',
        'tracking_number',
        'shipping_info',
        'cj_response_data',
        'submitted_to_cj_at',
        'confirmed_by_cj_at',
        'shipped_by_cj_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit' => 'decimal:2',
        'shipping_info' => 'array',
        'cj_response_data' => 'array',
        'submitted_to_cj_at' => 'datetime',
        'confirmed_by_cj_at' => 'datetime',
        'shipped_by_cj_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the associated order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get order items
     */
    public function items()
    {
        return $this->hasMany(DropshippingOrderItem::class);
    }

    /**
     * Calculate profit
     */
    public function calculateProfit()
    {
        return $this->selling_price - $this->cost_price;
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('cj_order_status', $status);
    }

    /**
     * Scope to get pending orders
     */
    public function scopePending($query)
    {
        return $query->where('cj_order_status', 'pending');
    }

    /**
     * Scope to get confirmed orders
     */
    public function scopeConfirmed($query)
    {
        return $query->where('cj_order_status', 'confirmed');
    }

    /**
     * Scope to get shipped orders
     */
    public function scopeShipped($query)
    {
        return $query->where('cj_order_status', 'shipped');
    }

    /**
     * Get order amount
     */
    public function getOrderAmountAttribute()
    {
        return $this->items()->sum('total_selling_price') ?? $this->selling_price;
    }
}
