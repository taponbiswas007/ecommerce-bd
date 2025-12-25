<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportCompany extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'contact', 'is_active'];

    public function packageRates()
    {
        return $this->hasMany(PackageRate::class);
    }
}
