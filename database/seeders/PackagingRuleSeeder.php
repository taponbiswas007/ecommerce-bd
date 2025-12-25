<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackagingRule;
use App\Models\Product;

class PackagingRuleSeeder extends Seeder
{
    public function run()
    {
        // Try to seed for a product if exists
        $product = Product::first();
        if (!$product) return;

        // Example: for products sold in KG, set Roll=10KG, Cartoon=50KG
        PackagingRule::firstOrCreate([
            'product_id' => $product->id,
            'unit_name' => 'Cartoon',
        ], [
            'units_per' => 50,
            'priority' => 20,
            'is_active' => true,
        ]);

        PackagingRule::firstOrCreate([
            'product_id' => $product->id,
            'unit_name' => 'Roll',
        ], [
            'units_per' => 10,
            'priority' => 10,
            'is_active' => true,
        ]);
    }
}
