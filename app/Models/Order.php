<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'discount_amount',
        'shipping_charge',
        'tax_amount',
        'vat_amount',
        'ait_amount',
        'total_amount',
        'payment_method',
        'payment_account_id',
        'payment_status',
        'transaction_id',
        'order_status',
        'negotiation_status',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_district',
        'shipping_upazila',
        'shipping_address',
        'transport_name',
        'transport_company_id',
        'shipping_method',
        'tracking_number',
        'tracking_url',
        'delivery_document',
        'terms_accepted',
        'customer_notes',
        'admin_notes',
        'additional_transport_cost',
        'additional_carrying_cost',
        'bank_transfer_cost',
        'additional_other_cost',
        'admin_discount_amount',
        'negotiated_total_amount',
        'payment_instructions',
        'payment_reference',
        'payment_proof_path',
        'is_self_delivery_risk',
        'negotiation_updated_at',
        'quoted_by_admin_id',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'ait_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'additional_transport_cost' => 'decimal:2',
        'additional_carrying_cost' => 'decimal:2',
        'bank_transfer_cost' => 'decimal:2',
        'additional_other_cost' => 'decimal:2',
        'admin_discount_amount' => 'decimal:2',
        'negotiated_total_amount' => 'decimal:2',
        'terms_accepted' => 'boolean',
        'is_self_delivery_risk' => 'boolean',
        'negotiation_updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transportCompany()
    {
        return $this->belongsTo(\App\Models\TransportCompany::class, 'transport_company_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('status_date', 'desc');
    }

    public function latestStatusHistory()
    {
        return $this->hasOne(OrderStatusHistory::class)->latestOfMany('status_date');
    }

    public function quotedByAdmin()
    {
        return $this->belongsTo(User::class, 'quoted_by_admin_id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class, 'payment_account_id');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match ($this->order_status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'ready_to_ship' => 'secondary',
            'shipped' => 'info',
            'delivered' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'dark',
            default => 'secondary',
        };
    }

    public function getPayableAmountAttribute(): float
    {
        return (float) ($this->negotiated_total_amount ?? $this->total_amount);
    }

    public function getTotalAdjustmentsAttribute(): float
    {
        return (float) $this->additional_carrying_cost
            + (float) $this->bank_transfer_cost
            + (float) $this->additional_other_cost
            - (float) $this->admin_discount_amount;
    }
}
