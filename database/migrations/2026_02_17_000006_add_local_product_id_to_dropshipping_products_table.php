<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dropshipping_products', function (Blueprint $table) {
            $table->foreignId('local_product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete()
                ->after('cj_product_id');
        });
    }

    public function down(): void
    {
        Schema::table('dropshipping_products', function (Blueprint $table) {
            $table->dropForeign(['local_product_id']);
            $table->dropColumn('local_product_id');
        });
    }
};
