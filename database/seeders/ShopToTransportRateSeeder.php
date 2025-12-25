<?php

namespace Database\Seeders;

use App\Models\ShopToTransportRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopToTransportRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default global rates (no district/upazila) - used as fallback
        ShopToTransportRate::create([
            'package_type' => 'Cartoon',
            'district' => null,
            'upazila' => null,
            'rate' => 50,
            'is_active' => true,
        ]);

        ShopToTransportRate::create([
            'package_type' => 'Roll',
            'district' => null,
            'upazila' => null,
            'rate' => 50,
            'is_active' => true,
        ]);

        ShopToTransportRate::create([
            'package_type' => 'Loose',
            'district' => null,
            'upazila' => null,
            'rate' => 50,
            'is_active' => true,
        ]);

        // Sample location-specific rates (override global defaults)
        ShopToTransportRate::create([
            'package_type' => 'Cartoon',
            'district' => 'Dhaka',
            'upazila' => null,
            'rate' => 40,
            'is_active' => true,
        ]);

        ShopToTransportRate::create([
            'package_type' => 'Roll',
            'district' => 'Dhaka',
            'upazila' => null,
            'rate' => 40,
            'is_active' => true,
        ]);

        ShopToTransportRate::create([
            'package_type' => 'Cartoon',
            'district' => 'Chattogram',
            'upazila' => null,
            'rate' => 45,
            'is_active' => true,
        ]);
    }
}
