<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatAitSetting extends Model
{
    use SoftDeletes;

    protected $table = 'vat_ait_settings';

    protected $fillable = [
        'default_vat_percentage',
        'vat_enabled',
        'vat_included_in_price',
        'default_ait_percentage',
        'ait_enabled',
        'ait_included_in_price',
        'ait_exempt_categories',
        'notes',
        'effective_from',
    ];

    protected $casts = [
        'vat_enabled' => 'boolean',
        'vat_included_in_price' => 'boolean',
        'ait_enabled' => 'boolean',
        'ait_included_in_price' => 'boolean',
        'default_vat_percentage' => 'decimal:2',
        'default_ait_percentage' => 'decimal:2',
        'effective_from' => 'datetime',
    ];

    /**
     * Get the current active VAT/AIT settings
     */
    public static function current()
    {
        return self::where(function ($query) {
            $query->whereNull('effective_from')
                ->orWhere('effective_from', '<=', now());
        })
            ->whereNull('deleted_at')
            ->latest('effective_from')
            ->latest('id')
            ->first() ?? self::createDefaults();
    }

    /**
     * Create default settings if none exist
     */
    public static function createDefaults()
    {
        return self::create([
            'default_vat_percentage' => 15.00,
            'vat_enabled' => true,
            'vat_included_in_price' => true,
            'default_ait_percentage' => 2.00,
            'ait_enabled' => true,
            'ait_included_in_price' => false,
            'effective_from' => now(),
        ]);
    }

    /**
     * Check if a category is exempt from AIT
     */
    public function isCategoryAitExempt($categoryId)
    {
        if (!$this->ait_exempt_categories) {
            return false;
        }

        $exemptCategories = explode(',', $this->ait_exempt_categories);
        return in_array($categoryId, array_map('trim', $exemptCategories));
    }

    /**
     * Get exempt categories as array
     */
    public function getExemptCategoriesArray()
    {
        if (!$this->ait_exempt_categories) {
            return [];
        }

        return array_map('trim', explode(',', $this->ait_exempt_categories));
    }
}
