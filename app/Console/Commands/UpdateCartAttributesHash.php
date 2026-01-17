<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCartAttributesHash extends Command
{
    protected $signature = 'cart:update-attributes-hash';
    protected $description = 'Update attributes_hash for all cart rows based on attributes';

    public function handle()
    {
        $this->info('Updating attributes_hash for all cart rows...');
        $carts = DB::table('carts')->get();
        $updated = 0;
        foreach ($carts as $cart) {
            $attributes = json_decode($cart->attributes, true);
            if (is_array($attributes)) {
                ksort($attributes);
                $hash = md5(json_encode($attributes));
            } else {
                $hash = md5($cart->attributes);
            }
            DB::table('carts')->where('id', $cart->id)->update(['attributes_hash' => $hash]);
            $updated++;
        }
        $this->info("Updated $updated cart rows.");
    }
}
