<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCartAttributesHash extends Command
{
    protected $signature = 'cart:update-attributes-hash';
    protected $description = 'Update attributes_hash for all cart items using robust normalization';

    public function handle()
    {
        $this->info('Updating attributes_hash for all cart items...');
        $carts = DB::table('carts')->get();
        $normalize = function ($arr) {
            if (!is_array($arr)) return json_encode($arr);
            $deepSortAndStringify = function (&$array) use (&$deepSortAndStringify) {
                if (!is_array($array)) return;
                ksort($array);
                foreach ($array as $k => &$value) {
                    if (is_array($value)) {
                        $deepSortAndStringify($value);
                    } else {
                        $value = trim((string)$value);
                    }
                }
            };
            $copy = $arr;
            $deepSortAndStringify($copy);
            return json_encode($copy);
        };
        $updated = 0;
        foreach ($carts as $cart) {
            $attributes = json_decode($cart->attributes, true);
            $hash = md5($normalize($attributes));
            DB::table('carts')->where('id', $cart->id)->update(['attributes_hash' => $hash]);
            $updated++;
        }
        $this->info("Updated $updated cart items.");
    }
}
