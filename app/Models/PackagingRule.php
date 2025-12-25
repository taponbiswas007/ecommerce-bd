<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'unit_name',
        'units_per',
        'priority',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
