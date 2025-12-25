<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportCompany;
use App\Models\PackageRate;

class TransportSeeder extends Seeder
{
    public function run()
    {
        $tc = TransportCompany::firstOrCreate(['name' => 'Default Transport'], ['slug' => 'default-transport']);
        PackageRate::firstOrCreate([
            'transport_company_id' => $tc->id,
            'package_type' => 'Cartoon',
            'district' => null,
            'upazila' => null,
        ], ['rate' => 200]);
        PackageRate::firstOrCreate([
            'transport_company_id' => $tc->id,
            'package_type' => 'Roll',
            'district' => null,
            'upazila' => null,
        ], ['rate' => 80]);
        PackageRate::firstOrCreate([
            'transport_company_id' => $tc->id,
            'package_type' => 'Loose',
            'district' => null,
            'upazila' => null,
        ], ['rate' => 50]);

        $other = TransportCompany::firstOrCreate(['name' => 'S R Travels'], ['slug' => 'sr-travels']);
        PackageRate::firstOrCreate([
            'transport_company_id' => $other->id,
            'package_type' => 'Cartoon',
        ], ['rate' => 240]);
        PackageRate::firstOrCreate([
            'transport_company_id' => $other->id,
            'package_type' => 'Roll',
        ], ['rate' => 90]);
        PackageRate::firstOrCreate([
            'transport_company_id' => $other->id,
            'package_type' => 'Loose',
        ], ['rate' => 60]);
    }
}
