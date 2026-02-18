<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, you need to use a generated column for JSON uniqueness
        Schema::table('carts', function (Blueprint $table) {
            // Add a generated column for normalized attributes (as string)
            $table->string('attributes_hash', 32)->nullable()->after('attributes');
        });

        // Fill the new column for existing rows
        DB::table('carts')->get()->each(function ($row) {
            $attributes = json_decode($row->attributes, true);
            if (is_array($attributes)) {
                ksort($attributes);
                $hash = md5(json_encode($attributes));
            } else {
                $hash = md5($row->attributes);
            }
            DB::table('carts')->where('id', $row->id)->update(['attributes_hash' => $hash]);
        });

        // Add unique index (user_id, session_id, product_id, attributes_hash)
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'session_id', 'product_id', 'attributes_hash'], 'cart_unique_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('cart_unique_combination');
            $table->dropColumn('attributes_hash');
        });
    }
};
