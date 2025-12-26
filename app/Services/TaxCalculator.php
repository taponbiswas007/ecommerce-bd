<?php

namespace App\Services;

use App\Models\Product;
use App\Models\VatAitSetting;

class TaxCalculator
{
    /**
     * Calculate VAT for a product
     *
     * @param Product $product
     * @param float $price The base price to calculate VAT on
     * @return array ['vat_amount' => float, 'vat_percentage' => float, 'included' => bool]
     */
    public static function calculateVat(Product $product, $price)
    {
        $vatPercentage = $product->getEffectiveVatPercentage();
        $isIncluded = $product->isVatIncluded();

        if ($vatPercentage <= 0) {
            return [
                'vat_amount' => 0,
                'vat_percentage' => 0,
                'included' => $isIncluded,
            ];
        }

        // If VAT is included, calculate it from the total price
        if ($isIncluded) {
            $vatAmount = ($price * $vatPercentage) / (100 + $vatPercentage);
        } else {
            // If VAT is not included, calculate it on the base price
            $vatAmount = ($price * $vatPercentage) / 100;
        }

        return [
            'vat_amount' => round($vatAmount, 2),
            'vat_percentage' => $vatPercentage,
            'included' => $isIncluded,
        ];
    }

    /**
     * Calculate AIT for a product
     *
     * @param Product $product
     * @param float $price The base price to calculate AIT on
     * @return array ['ait_amount' => float, 'ait_percentage' => float, 'included' => bool]
     */
    public static function calculateAit(Product $product, $price)
    {
        $aitPercentage = $product->getEffectiveAitPercentage();
        $isIncluded = $product->isAitIncluded();

        if ($aitPercentage <= 0) {
            return [
                'ait_amount' => 0,
                'ait_percentage' => 0,
                'included' => $isIncluded,
            ];
        }

        // If AIT is included, calculate it from the total price
        if ($isIncluded) {
            $aitAmount = ($price * $aitPercentage) / (100 + $aitPercentage);
        } else {
            // If AIT is not included, calculate it on the base price
            $aitAmount = ($price * $aitPercentage) / 100;
        }

        return [
            'ait_amount' => round($aitAmount, 2),
            'ait_percentage' => $aitPercentage,
            'included' => $isIncluded,
        ];
    }

    /**
     * Calculate both VAT and AIT for a product
     *
     * @param Product $product
     * @param float $basePrice The product's base price
     * @param int $quantity The quantity
     * @return array Comprehensive tax calculation
     */
    public static function calculateTaxes(Product $product, $basePrice, $quantity = 1)
    {
        $totalPrice = $basePrice * $quantity;

        $vat = self::calculateVat($product, $totalPrice);
        $ait = self::calculateAit($product, $totalPrice);

        // Calculate the final price
        $finalPrice = $totalPrice;

        if (!$vat['included'] && $vat['vat_amount'] > 0) {
            $finalPrice += $vat['vat_amount'];
        }

        if (!$ait['included'] && $ait['ait_amount'] > 0) {
            $finalPrice += $ait['ait_amount'];
        }

        return [
            'base_price' => $basePrice,
            'total_base_price' => $totalPrice,
            'vat' => $vat,
            'ait' => $ait,
            'final_price' => round($finalPrice, 2),
            'quantity' => $quantity,
            'summary' => [
                'base_price_display' => $basePrice,
                'vat_percentage' => $vat['vat_percentage'],
                'vat_included' => $vat['included'],
                'ait_percentage' => $ait['ait_percentage'],
                'ait_included' => $ait['included'],
                'total_tax_percentage' => $vat['vat_percentage'] + $ait['ait_percentage'],
            ],
        ];
    }

    /**
     * Format tax information for display
     *
     * @param Product $product
     * @param float $price
     * @return string HTML formatted tax info
     */
    public static function getTaxInfoHtml(Product $product, $price)
    {
        $taxes = self::calculateTaxes($product, $price);
        $html = '';

        if ($taxes['vat']['vat_percentage'] > 0) {
            $html .= '<small class="text-muted">';
            if ($taxes['vat']['included']) {
                $html .= "VAT ({$taxes['vat']['vat_percentage']}%) included";
            } else {
                $html .= "VAT ({$taxes['vat']['vat_percentage']}%) will be added";
            }
            $html .= '</small><br>';
        }

        if ($taxes['ait']['ait_percentage'] > 0) {
            $html .= '<small class="text-muted">';
            if ($taxes['ait']['included']) {
                $html .= "AIT ({$taxes['ait']['ait_percentage']}%) included";
            } else {
                $html .= "AIT ({$taxes['ait']['ait_percentage']}%) will be added";
            }
            $html .= '</small>';
        }

        return $html;
    }

    /**
     * Get formatted price with taxes
     *
     * @param Product $product
     * @param float $basePrice
     * @return array With formatted strings for display
     */
    public static function getPriceBreakdown(Product $product, $basePrice)
    {
        $taxes = self::calculateTaxes($product, $basePrice);

        return [
            'base_price' => $basePrice,
            'base_price_formatted' => '৳' . number_format($basePrice, 2),
            'vat_amount' => $taxes['vat']['vat_amount'],
            'vat_amount_formatted' => $taxes['vat']['vat_amount'] > 0 ? '৳' . number_format($taxes['vat']['vat_amount'], 2) : '-',
            'ait_amount' => $taxes['ait']['ait_amount'],
            'ait_amount_formatted' => $taxes['ait']['ait_amount'] > 0 ? '৳' . number_format($taxes['ait']['ait_amount'], 2) : '-',
            'final_price' => $taxes['final_price'],
            'final_price_formatted' => '৳' . number_format($taxes['final_price'], 2),
            'vat_percentage' => $taxes['vat']['vat_percentage'],
            'vat_included' => $taxes['vat']['included'],
            'ait_percentage' => $taxes['ait']['ait_percentage'],
            'ait_included' => $taxes['ait']['included'],
        ];
    }

    /**
     * Get tax summary by product (useful for cart/order)
     *
     * @param array $items Array of ['product' => Product, 'quantity' => int, 'price' => float]
     * @return array Tax summary
     */
    public static function getSummaryForItems($items)
    {
        $totalBasePrice = 0;
        $totalVat = 0;
        $totalAit = 0;

        foreach ($items as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'] ?? 1;
            $price = $item['price'];

            $taxes = self::calculateTaxes($product, $price, $quantity);

            $totalBasePrice += $taxes['total_base_price'];
            $totalVat += $taxes['vat']['vat_amount'];
            $totalAit += $taxes['ait']['ait_amount'];
        }

        return [
            'base_price' => round($totalBasePrice, 2),
            'vat_amount' => round($totalVat, 2),
            'ait_amount' => round($totalAit, 2),
            'total_tax' => round($totalVat + $totalAit, 2),
            'final_price' => round($totalBasePrice + $totalVat + $totalAit, 2),
        ];
    }
}
