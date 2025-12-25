<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_company_id',
        'package_type',
        'district',
        'upazila',
        'rate',
        'is_active',
    ];

    public function transportCompany()
    {
        return $this->belongsTo(TransportCompany::class);
    }
}
