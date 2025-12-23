<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'min_quantity',
        'max_quantity',
        'price',
    ];

    protected $casts = [
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getQuantityRangeAttribute()
    {
        if ($this->max_quantity) {
            return "{$this->min_quantity} - {$this->max_quantity}";
        }

        return "{$this->min_quantity}+";
    }

    public function getFormattedPriceAttribute()
    {
        return config('app.currency_symbol') . number_format($this->price, 2);
    }

    // Scopes
    public function scopeForQuantity($query, $quantity)
    {
        return $query->where('min_quantity', '<=', $quantity)
            ->where(function ($q) use ($quantity) {
                $q->where('max_quantity', '>=', $quantity)
                    ->orWhereNull('max_quantity');
            })
            ->orderBy('min_quantity', 'desc')
            ->first();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('min_quantity');
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'nullable|integer|gt:min_quantity',
            'price' => 'required|numeric|min:0',
        ];
    }
}
