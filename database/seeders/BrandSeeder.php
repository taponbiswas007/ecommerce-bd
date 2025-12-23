<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Apple Inc. is an American multinational technology company that specializes in consumer electronics, software, and online services.',
                'logo' => 'brands/apple.png',
                'website' => 'https://www.apple.com',
                'country' => 'USA',
                'founded_year' => 1976,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Samsung Electronics Co., Ltd. is a South Korean multinational electronics company headquartered in Suwon, South Korea.',
                'logo' => 'brands/samsung.png',
                'website' => 'https://www.samsung.com',
                'country' => 'South Korea',
                'founded_year' => 1938,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Sony Corporation is a Japanese multinational conglomerate corporation headquartered in Tokyo, Japan.',
                'logo' => 'brands/sony.png',
                'website' => 'https://www.sony.com',
                'country' => 'Japan',
                'founded_year' => 1946,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'LG Electronics Inc. is a South Korean multinational electronics company headquartered in Seoul, South Korea.',
                'logo' => 'brands/lg.png',
                'website' => 'https://www.lg.com',
                'country' => 'South Korea',
                'founded_year' => 1958,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft',
                'description' => 'Microsoft Corporation is an American multinational technology corporation which produces computer software, consumer electronics, personal computers, and related services.',
                'logo' => 'brands/microsoft.png',
                'website' => 'https://www.microsoft.com',
                'country' => 'USA',
                'founded_year' => 1975,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Dell Technologies is an American multinational technology company that develops, sells, repairs, and supports computers and related products and services.',
                'logo' => 'brands/dell.png',
                'website' => 'https://www.dell.com',
                'country' => 'USA',
                'founded_year' => 1984,
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'HP Inc. is an American multinational information technology company that provides personal computing and other access devices, imaging and printing products.',
                'logo' => 'brands/hp.png',
                'website' => 'https://www.hp.com',
                'country' => 'USA',
                'founded_year' => 1939,
                'is_featured' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'Lenovo Group Limited, often shortened to Lenovo, is a Chinese multinational technology company specializing in designing, manufacturing, and marketing consumer electronics.',
                'logo' => 'brands/lenovo.png',
                'website' => 'https://www.lenovo.com',
                'country' => 'China',
                'founded_year' => 1984,
                'is_featured' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Xiaomi Corporation is a Chinese designer and manufacturer of consumer electronics and related software, home appliances, and household items.',
                'logo' => 'brands/xiaomi.png',
                'website' => 'https://www.mi.com',
                'country' => 'China',
                'founded_year' => 2010,
                'is_featured' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Asus',
                'slug' => 'asus',
                'description' => 'ASUS is a Taiwan-based, multinational computer hardware and consumer electronics company that was established in 1989.',
                'logo' => 'brands/asus.png',
                'website' => 'https://www.asus.com',
                'country' => 'Taiwan',
                'founded_year' => 1989,
                'is_featured' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
