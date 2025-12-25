<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\PackagingRule;
use App\Models\TransportCompany;
use App\Models\PackageRate;
use App\Services\ShippingCalculator;

class ShippingCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversion_and_cost()
    {
        // Create product
        $category = \App\Models\Category::first() ?? \App\Models\Category::create(['name' => 'Test Cat', 'slug' => 'test-cat']);
        $unit = \App\Models\Unit::first() ?? \App\Models\Unit::create(['name' => 'kg', 'symbol' => 'kg']);
        $p = Product::create(['name' => 'Test Cable', 'slug' => 'test-cable', 'base_price' => 100, 'unit_id' => $unit->id, 'category_id' => $category->id, 'is_active' => true]);

        // Add rules: Cartoon = 50, Roll = 10
        PackagingRule::create(['product_id' => $p->id, 'unit_name' => 'Cartoon', 'units_per' => 50, 'priority' => 20]);
        PackagingRule::create(['product_id' => $p->id, 'unit_name' => 'Roll', 'units_per' => 10, 'priority' => 10]);

        // Create transport company and rates
        $tc = TransportCompany::create(['name' => 'Test Transport']);
        PackageRate::create(['transport_company_id' => $tc->id, 'package_type' => 'Cartoon', 'rate' => 200]);
        PackageRate::create(['transport_company_id' => $tc->id, 'package_type' => 'Roll', 'rate' => 80]);
        PackageRate::create(['transport_company_id' => $tc->id, 'package_type' => 'Loose', 'rate' => 50]);

        // Create shop-to-transport rates (50 TK per package)
        \App\Models\ShopToTransportRate::create(['package_type' => 'Cartoon', 'rate' => 50]);
        \App\Models\ShopToTransportRate::create(['package_type' => 'Roll', 'rate' => 50]);
        \App\Models\ShopToTransportRate::create(['package_type' => 'Loose', 'rate' => 50]);

        // Build fake cart items collection with one item of quantity 73 (sales unit)
        $item = (object)['product' => $p, 'quantity' => 73];
        $items = collect([$item]);

        $calc = new ShippingCalculator();
        $res = $calc->calculate($items, 'Dhaka', 'SomeUpazila', $tc->id, 'transport');

        // Expect packages: Cartoon 1, Roll 2, Loose 1 (total 4 packages)
        $this->assertEquals(1, $res['packages']['Cartoon'] ?? 0);
        $this->assertEquals(2, $res['packages']['Roll'] ?? 0);
        $this->assertEquals(1, $res['packages']['Loose'] ?? 0);

        // Cost should be:
        // Cartoon: 200*1 = 200
        // Roll: 80*2 = 160
        // Loose: 50*1 = 50
        // Shop-to-transport (per package): 50*(1+2+1) = 200
        // Total: 200 + 160 + 50 + 200 = 610
        $expected = 200 + (80 * 2) + 50 + (50 * 4); // 4 total packages
        $this->assertEquals($expected, $res['total']);
        $this->assertEquals(610, $res['total']);
    }
}
