<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopToTransportRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_type',
        'district',
        'upazila',
        'rate',
        'is_active',
    ];
}
