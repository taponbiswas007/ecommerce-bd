<?php

use App\Models\Product;
use App\Models\VatAitSetting;
use App\Services\TaxCalculator;

if (!function_exists('getProductVatPercentage')) {
    /**
     * Get VAT percentage for a product
     */
    function getProductVatPercentage(Product $product): float
    {
        return $product->getEffectiveVatPercentage();
    }
}

if (!function_exists('getProductAitPercentage')) {
    /**
     * Get AIT percentage for a product
     */
    function getProductAitPercentage(Product $product): float
    {
        return $product->getEffectiveAitPercentage();
    }
}

if (!function_exists('isProductVatIncluded')) {
    /**
     * Check if product VAT is included in price
     */
    function isProductVatIncluded(Product $product): bool
    {
        return $product->isVatIncluded();
    }
}

if (!function_exists('isProductAitIncluded')) {
    /**
     * Check if product AIT is included in price
     */
    function isProductAitIncluded(Product $product): bool
    {
        return $product->isAitIncluded();
    }
}

if (!function_exists('calculateProductTaxes')) {
    /**
     * Calculate VAT and AIT for a product
     */
    function calculateProductTaxes(Product $product, float $basePrice, int $quantity = 1): array
    {
        return TaxCalculator::calculateTaxes($product, $basePrice, $quantity);
    }
}

if (!function_exists('getProductPriceBreakdown')) {
    /**
     * Get formatted price breakdown for a product
     */
    function getProductPriceBreakdown(Product $product, float $basePrice): array
    {
        return TaxCalculator::getPriceBreakdown($product, $basePrice);
    }
}

if (!function_exists('calculateVat')) {
    /**
     * Calculate VAT amount for a price
     */
    function calculateVat(Product $product, float $price): array
    {
        return TaxCalculator::calculateVat($product, $price);
    }
}

if (!function_exists('calculateAit')) {
    /**
     * Calculate AIT amount for a price
     */
    function calculateAit(Product $product, float $price): array
    {
        return TaxCalculator::calculateAit($product, $price);
    }
}

if (!function_exists('getCurrentVatAitSettings')) {
    /**
     * Get current VAT/AIT settings
     */
    function getCurrentVatAitSettings(): VatAitSetting
    {
        return VatAitSetting::current();
    }
}

if (!function_exists('formatTaxPercentage')) {
    /**
     * Format tax percentage for display
     */
    function formatTaxPercentage(float $percentage): string
    {
        return number_format($percentage, 2) . '%';
    }
}

if (!function_exists('formatTaxAmount')) {
    /**
     * Format tax amount as BDT
     */
    function formatTaxAmount(float $amount): string
    {
        return '৳' . number_format($amount, 2);
    }
}

if (!function_exists('getTaxSummaryForCartItems')) {
    /**
     * Get tax summary for multiple cart items
     */
    function getTaxSummaryForCartItems(array $items): array
    {
        return TaxCalculator::getSummaryForItems($items);
    }
}
