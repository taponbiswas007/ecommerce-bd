<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'previous_status',
        'notes',
        'document_path',
        'document_name',
        'updated_by',
        'location',
        'status_date',
    ];

    protected $casts = [
        'status_date' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Get status display name in Bengali and English
    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'pending' => 'অর্ডার প্রাপ্ত হয়েছে (Pending)',
            'confirmed' => 'অর্ডার নিশ্চিত করা হয়েছে (Confirmed)',
            'processing' => 'প্রসেসিং চলছে (Processing)',
            'ready_to_ship' => 'শিপমেন্টের জন্য প্রস্তুত (Ready to Ship)',
            'shipped' => 'পাঠানো হয়েছে (Shipped)',
            'delivered' => 'ডেলিভারি সম্পন্ন (Delivered)',
            'completed' => 'সম্পূর্ণ হয়েছে (Completed)',
            'cancelled' => 'বাতিল করা হয়েছে (Cancelled)',
            'refunded' => 'রিফান্ড করা হয়েছে (Refunded)',
            default => ucfirst($this->status),
        };
    }

    // Get status icon
    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'pending' => 'fas fa-clock',
            'confirmed' => 'fas fa-check-circle',
            'processing' => 'fas fa-cogs',
            'ready_to_ship' => 'fas fa-box',
            'shipped' => 'fas fa-shipping-fast',
            'delivered' => 'fas fa-home',
            'completed' => 'fas fa-check-double',
            'cancelled' => 'fas fa-times-circle',
            'refunded' => 'fas fa-undo',
            default => 'fas fa-info-circle',
        };
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
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
}
