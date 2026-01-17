<?php

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\MergeDuplicateCartItems;

return [
    'commands' => [
        MergeDuplicateCartItems::class,
        \App\Console\Commands\UpdateCartAttributesHash::class,
    ],
];

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register custom command
Artisan::starting(function ($artisan) {
    $artisan->resolve(MergeDuplicateCartItems::class);
});
