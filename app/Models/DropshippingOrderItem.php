<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropshippingOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dropshipping_order_id',
        'dropshipping_product_id',
        'sku',
        'quantity',
        'unit_cost_price',
        'unit_selling_price',
        'total_cost_price',
        'total_selling_price',
    ];

    protected $casts = [
        'unit_cost_price' => 'decimal:2',
        'unit_selling_price' => 'decimal:2',
        'total_cost_price' => 'decimal:2',
        'total_selling_price' => 'decimal:2',
    ];

    public $timestamps = true;

    /**
     * Get the dropshipping order
     */
    public function dropshippingOrder()
    {
        return $this->belongsTo(DropshippingOrder::class);
    }

    /**
     * Get the dropshipping product
     */
    public function product()
    {
        return $this->belongsTo(DropshippingProduct::class, 'dropshipping_product_id');
    }

    /**
     * Calculate profit for this item
     */
    public function getProfit()
    {
        return $this->total_selling_price - $this->total_cost_price;
    }
}
