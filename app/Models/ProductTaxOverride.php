<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTaxOverride extends Model
{
    use SoftDeletes;

    protected $table = 'product_tax_overrides';

    protected $fillable = [
        'product_id',
        'override_vat',
        'vat_percentage',
        'vat_included_in_price',
        'override_ait',
        'ait_percentage',
        'ait_included_in_price',
        'vat_exempt',
        'ait_exempt',
        'reason',
        'effective_from',
        'effective_until',
    ];

    protected $casts = [
        'override_vat' => 'boolean',
        'override_ait' => 'boolean',
        'vat_exempt' => 'boolean',
        'ait_exempt' => 'boolean',
        'vat_included_in_price' => 'boolean',
        'ait_included_in_price' => 'boolean',
        'vat_percentage' => 'decimal:2',
        'ait_percentage' => 'decimal:2',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    /**
     * Get the product this override belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if this override is currently active
     */
    public function isActive()
    {
        $now = now();

        if ($this->effective_from && $this->effective_from->isFuture()) {
            return false;
        }

        if ($this->effective_until && $this->effective_until->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get effective VAT percentage
     */
    public function getEffectiveVatPercentage()
    {
        if (!$this->isActive()) {
            return null;
        }

        if ($this->vat_exempt) {
            return 0;
        }

        if ($this->override_vat && $this->vat_percentage !== null) {
            return $this->vat_percentage;
        }

        return null;
    }

    /**
     * Get effective AIT percentage
     */
    public function getEffectiveAitPercentage()
    {
        if (!$this->isActive()) {
            return null;
        }

        if ($this->ait_exempt) {
            return 0;
        }

        if ($this->override_ait && $this->ait_percentage !== null) {
            return $this->ait_percentage;
        }

        return null;
    }

    /**
     * Get whether VAT is included in price (or null to use global setting)
     */
    public function getVatIncludedInPrice()
    {
        if (!$this->isActive()) {
            return null;
        }

        return $this->vat_included_in_price;
    }

    /**
     * Get whether AIT is included in price (or null to use global setting)
     */
    public function getAitIncludedInPrice()
    {
        if (!$this->isActive()) {
            return null;
        }

        return $this->ait_included_in_price;
    }
}
