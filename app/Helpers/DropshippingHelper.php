<?php

namespace App\Helpers;

use App\Models\DropshippingProduct;
use App\Models\DropshippingOrder;
use App\Models\DropshippingSetting;

class DropshippingHelper
{
    /**
     * Check if dropshipping is enabled
     */
    public static function isEnabled(): bool
    {
        return DropshippingSetting::getSetting('enable_dropshipping', '1') == '1';
    }

    /**
     * Check if product is a dropshipping product
     */
    public static function isDropshippingProduct($product): bool
    {
        if (is_null($product)) {
            return false;
        }
        return $product instanceof DropshippingProduct || !empty($product->cj_product_id);
    }

    /**
     * Get profit margin percentage for a dropshipping order
     */
    public static function getProfitMargin($dropshippingOrder): float
    {
        if ($dropshippingOrder->selling_price == 0) {
            return 0;
        }
        return ($dropshippingOrder->profit / $dropshippingOrder->selling_price) * 100;
    }

    /**
     * Get profit margin percentage for a product
     */
    public static function getProductProfitMargin($product): float
    {
        if ($product->selling_price == 0) {
            return 0;
        }
        return ($product->profit_margin / $product->selling_price) * 100;
    }

    /**
     * Get default profit margin from settings
     */
    public static function getDefaultMarginPercent(): int
    {
        return (int) DropshippingSetting::getSetting('default_profit_margin_percent', 20);
    }

    /**
     * Calculate selling price from cost and margin percent
     */
    public static function calculateSellingPrice($costPrice, $marginPercent = null): float
    {
        if ($marginPercent === null) {
            $marginPercent = self::getDefaultMarginPercent();
        }
        return $costPrice * (1 + ($marginPercent / 100));
    }

    /**
     * Get total profit from all dropshipping orders
     */
    public static function getTotalProfit(): float
    {
        return DropshippingOrder::sum('profit') ?? 0;
    }

    /**
     * Get total revenue from all dropshipping orders
     */
    public static function getTotalRevenue(): float
    {
        return DropshippingOrder::sum('selling_price') ?? 0;
    }

    /**
     * Get total cost from all dropshipping orders
     */
    public static function getTotalCost(): float
    {
        return DropshippingOrder::sum('cost_price') ?? 0;
    }

    /**
     * Get order statistics
     */
    public static function getOrderStats(): array
    {
        return [
            'total' => DropshippingOrder::count(),
            'pending' => DropshippingOrder::pending()->count(),
            'confirmed' => DropshippingOrder::confirmed()->count(),
            'shipped' => DropshippingOrder::shipped()->count(),
            'total_profit' => self::getTotalProfit(),
            'total_revenue' => self::getTotalRevenue(),
            'total_cost' => self::getTotalCost(),
        ];
    }

    /**
     * Get product statistics
     */
    public static function getProductStats(): array
    {
        return [
            'total' => DropshippingProduct::count(),
            'active' => DropshippingProduct::where('is_active', true)->count(),
            'available' => DropshippingProduct::available()->count(),
            'out_of_stock' => DropshippingProduct::where('stock', 0)->count(),
        ];
    }

    /**
     * Format status for display
     */
    public static function formatStatus($status): string
    {
        return match ($status) {
            'pending' => '⏳ Pending',
            'confirmed' => '✓ Confirmed',
            'shipped' => '🚚 Shipped',
            'delivered' => '✓ Delivered',
            'cancelled' => '✗ Cancelled',
            default => ucfirst($status),
        };
    }

    /**
     * Get status badge class
     */
    public static function getStatusBadgeClass($status): string
    {
        return match ($status) {
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get status icon
     */
    public static function getStatusIcon($status): string
    {
        return match ($status) {
            'pending' => 'fa-hourglass-end',
            'confirmed' => 'fa-check-circle',
            'shipped' => 'fa-truck',
            'delivered' => 'fa-check-double',
            'cancelled' => 'fa-times-circle',
            default => 'fa-info-circle',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public static function canCancelOrder($dropshippingOrder): bool
    {
        return !in_array($dropshippingOrder->cj_order_status, ['delivered', 'cancelled']);
    }

    /**
     * Get available order actions
     */
    public static function getOrderActions($dropshippingOrder): array
    {
        $actions = ['sync'];

        if (self::canCancelOrder($dropshippingOrder)) {
            $actions[] = 'cancel';
        }

        if ($dropshippingOrder->cj_order_status === 'shipped') {
            $actions[] = 'track';
        }

        return $actions;
    }

    /**
     * Get profit color class
     */
    public static function getProfitColorClass($profit): string
    {
        if ($profit < 0) {
            return 'text-danger';
        } elseif ($profit == 0) {
            return 'text-muted';
        } else {
            return 'text-success';
        }
    }

    /**
     * Format currency for display
     */
    public static function formatCurrency($amount, $currency = '৳'): string
    {
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * Check if API is configured
     */
    public static function isApiConfigured(): bool
    {
        return !empty(DropshippingSetting::getSetting('cj_api_key')) &&
            !empty(DropshippingSetting::getSetting('cj_api_secret'));
    }

    /**
     * Get average order value
     */
    public static function getAverageOrderValue(): float
    {
        $count = DropshippingOrder::count();
        if ($count == 0) {
            return 0;
        }
        return self::getTotalRevenue() / $count;
    }

    /**
     * Get overall profit margin percentage
     */
    public static function getOverallProfitMargin(): float
    {
        $revenue = self::getTotalRevenue();
        if ($revenue == 0) {
            return 0;
        }
        return (self::getTotalProfit() / $revenue) * 100;
    }

    /**
     * Get best performing products
     */
    public static function getTopProducts($limit = 5): array
    {
        return DropshippingProduct::active()
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get recent orders
     */
    public static function getRecentOrders($limit = 10)
    {
        return DropshippingOrder::with(['order.user', 'items.product'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate remaining profit after all costs
     */
    public static function calculateNetProfit($dropshippingOrder): float
    {
        $profit = $dropshippingOrder->profit;
        // You can add additional costs here:
        // $profit -= $platformFees;
        // $profit -= $shippingSubsidy;
        return $profit;
    }

    /**
     * Get status timeline for order
     */
    public static function getStatusTimeline($dropshippingOrder): array
    {
        return [
            'submitted' => $dropshippingOrder->submitted_to_cj_at,
            'confirmed' => $dropshippingOrder->confirmed_by_cj_at,
            'shipped' => $dropshippingOrder->shipped_by_cj_at,
            'delivered' => $dropshippingOrder->delivered_at,
        ];
    }

    /**
     * Check if order is stuck (not updated in X hours)
     */
    public static function isOrderStuck($dropshippingOrder, $hours = 24): bool
    {
        $lastUpdate = $dropshippingOrder->updated_at;
        $isCompleted = in_array($dropshippingOrder->cj_order_status, ['delivered', 'cancelled']);

        if ($isCompleted) {
            return false;
        }

        return $lastUpdate->addHours($hours)->isPast();
    }

    /**
     * Get stuck orders that need attention
     */
    public static function getStuckOrders($hours = 24)
    {
        return DropshippingOrder::where('cj_order_status', '!=', 'delivered')
            ->where('cj_order_status', '!=', 'cancelled')
            ->where('updated_at', '<', now()->subHours($hours))
            ->get();
    }
}
