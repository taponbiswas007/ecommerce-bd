<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeDuplicateCartItems extends Command
{
    protected $signature = 'cart:merge-duplicates';
    protected $description = 'Merge duplicate cart items by product_id and attributes';

    public function handle()
    {
        $this->info('Merging duplicate cart items...');
        $carts = DB::table('carts')->get();
        $grouped = [];
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
        foreach ($carts as $cart) {
            $attributes = json_decode($cart->attributes, true);
            $hash = md5($normalize($attributes));
            $key = $cart->user_id . '|' . $cart->session_id . '|' . $cart->product_id . '|' . $hash;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $cart;
        }

        // Debug: Output all cart rows with the same product_id and attribute key
        $productIdToCheck = null;
        $attributeKeyToCheck = 'Size/SWG';
        foreach ($carts as $cart) {
            $attributes = json_decode($cart->attributes, true);
            if (is_array($attributes) && array_key_exists($attributeKeyToCheck, $attributes)) {
                if ($productIdToCheck === null) {
                    $productIdToCheck = $cart->product_id;
                }
                if ($cart->product_id == $productIdToCheck) {
                    $this->line('DEBUG: id=' . $cart->id . ' attributes=' . $cart->attributes . ' attributes_hash=' . $cart->attributes_hash);
                }
            }
        }
        $merged = 0;
        foreach ($grouped as $items) {
            if (count($items) > 1) {
                $main = array_shift($items);
                $totalQty = $main->quantity;
                foreach ($items as $dup) {
                    $totalQty += $dup->quantity;
                    DB::table('carts')->where('id', $dup->id)->delete();
                }
                DB::table('carts')->where('id', $main->id)->update(['quantity' => $totalQty]);
                $merged++;
            }
        }
        $this->info("Merged $merged duplicate cart item groups.");
    }
}
