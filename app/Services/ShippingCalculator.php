<?php

namespace App\Services;

use App\Models\PackageRate;
use App\Models\PackagingRule;
use App\Models\TransportCompany;

class ShippingCalculator
{
    /**
     * Convert line items (cart items) into packages and compute cost
     *
     * @param \Illuminate\Support\Collection $items (Cart items with product relation)
     * @param string $district
     * @param string $upazila
     * @param int|null $transportCompanyId
     * @param string $method 'transport'|'own'|'pickup'
     * @return array ['total' => float, 'breakdown' => [...], 'packages' => [...]]
     */
    public function calculate($items, $district, $upazila, $transportCompanyId = null, $method = 'transport')
    {
        // Handle manual / own delivery: return special response
        if ($method === 'own') {
            return [
                'total' => 0,
                'breakdown' => [],
                'packages' => [],
                'note' => 'Own delivery selected (charge to be confirmed)',
                'requires_manual_price' => true,
            ];
        }

        if ($method === 'pickup') {
            return [
                'total' => 0,
                'breakdown' => [],
                'packages' => [],
                'note' => 'Pickup from store',
            ];
        }

        // Build package counts across all items
        $packageCounts = []; // ['Cartoon' => qty, 'Roll' => qty, 'Loose' => qty]

        foreach ($items as $item) {
            $product = $item->product;
            $quantity = $item->quantity; // in product sales unit (e.g., KG or pieces)

            // Get packaging rules for product (ordered by priority desc)
            $rules = $product->packagingRules()->get();

            // If no rules, treat whole quantity as 'Loose'
            if ($rules->isEmpty()) {
                $packageCounts['Loose'] = ($packageCounts['Loose'] ?? 0) + $quantity;
                continue;
            }

            $remaining = $quantity;
            foreach ($rules as $rule) {
                if ($rule->units_per <= 0) continue;
                $count = floor($remaining / $rule->units_per);
                if ($count > 0) {
                    $packageCounts[$rule->unit_name] = ($packageCounts[$rule->unit_name] ?? 0) + $count;
                    $remaining -= $count * $rule->units_per;
                }
            }

            // anything left becomes a loose package (we can call it Loose)
            if ($remaining > 0) {
                $packageCounts['Loose'] = ($packageCounts['Loose'] ?? 0) + 1; // one loose package
            }
        }

        // Now compute cost by package type using PackageRate (prefers upazila-specific, then district, then global for that company)
        $total = 0.0;
        $breakdown = [];

        $transportCompanyId = $transportCompanyId ?: optional(TransportCompany::where('is_active', true)->first())->id;

        foreach ($packageCounts as $pkgType => $pkgQty) {
            // Try upazila-specific rate
            $rate = PackageRate::where('transport_company_id', $transportCompanyId)
                ->where('package_type', $pkgType)
                ->where('upazila', $upazila)
                ->where('is_active', true)
                ->value('rate');

            // Fallback to district
            if ($rate === null) {
                $rate = PackageRate::where('transport_company_id', $transportCompanyId)
                    ->where('package_type', $pkgType)
                    ->where('district', $district)
                    ->where('is_active', true)
                    ->value('rate');
            }

            // Fallback to company-global
            if ($rate === null) {
                $rate = PackageRate::where('transport_company_id', $transportCompanyId)
                    ->where('package_type', $pkgType)
                    ->whereNull('district')
                    ->whereNull('upazila')
                    ->where('is_active', true)
                    ->value('rate');
            }

            // Fallback to legacy DeliveryCharge or config defaults
            if ($rate === null) {
                // use 0 as fallback, but better be conservative and apply config default
                $rate = (float) config('shipping.default_package_rate', 50);
            }

            $cost = round($rate * $pkgQty, 2);
            $breakdown[] = ['package_type' => $pkgType, 'quantity' => $pkgQty, 'rate' => $rate, 'cost' => $cost];
            $total += $cost;
        }

        // Add shop -> transport fee per package (lookup from DB, fallback to config)
        $shopToTransportRate = 0;
        if ($transportCompanyId) {
            // Calculate shop-to-transport fee per package type (not per order)
            $stFeeBreakdown = [];
            foreach ($packageCounts as $pkgType => $pkgQty) {
                // Try upazila-specific rate
                $stRate = \App\Models\ShopToTransportRate::where('package_type', $pkgType)
                    ->where('upazila', $upazila)
                    ->where('is_active', true)
                    ->value('rate');

                // Fallback to district
                if ($stRate === null) {
                    $stRate = \App\Models\ShopToTransportRate::where('package_type', $pkgType)
                        ->where('district', $district)
                        ->where('is_active', true)
                        ->value('rate');
                }

                // Fallback to global default
                if ($stRate === null) {
                    $stRate = \App\Models\ShopToTransportRate::where('package_type', $pkgType)
                        ->whereNull('district')
                        ->whereNull('upazila')
                        ->where('is_active', true)
                        ->value('rate');
                }

                // Fallback to config
                if ($stRate === null) {
                    $stRate = (float) config('shipping.shop_to_transport_base', 50);
                }

                $stCost = round((float) $stRate * $pkgQty, 2);
                $shopToTransportRate += $stCost;
                $stFeeBreakdown[] = ['package_type' => $pkgType, 'quantity' => $pkgQty, 'st_rate' => $stRate, 'st_cost' => $stCost];
            }
        } else {
            $shopToTransportRate = (float) config('shipping.shop_to_transport_base', 50);
        }

        $total += $shopToTransportRate;

        return [
            'total' => round($total, 2),
            'breakdown' => $breakdown,
            'packages' => $packageCounts,
            'shop_to_transport' => $shopToTransportRate,
        ];
    }
}
