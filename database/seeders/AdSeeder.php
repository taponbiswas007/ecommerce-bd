<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = [
            [
                'text' => '⚡ Flash Sale: Get 30% OFF on all electronic gadgets!',
                'badge' => 'Hot Deal',
            ],
            [
                'text' => '🔋 Free Shipping on orders above $99',
                'badge' => 'Free Shipping',
            ],
            [
                'text' => '📱 New Arrival: Latest Smartphones with 2 Years Warranty',
                'badge' => 'New',
            ],
            [
                'text' => '💡 Energy Efficient Appliances - Save up to 40% on electricity',
                'badge' => 'Eco-Friendly',
            ],
            [
                'text' => '🎧 Wireless Earbuds with Noise Cancellation - Limited Stock',
                'badge' => 'Trending',
            ],
        ];

        foreach ($ads as $ad) {
            \App\Models\Ad::create($ad);
        }
    }
}
