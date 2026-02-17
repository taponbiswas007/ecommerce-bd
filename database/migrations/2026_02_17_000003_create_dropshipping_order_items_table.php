<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dropshipping_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dropshipping_order_id')->constrained('dropshipping_orders')->onDelete('cascade');
            $table->foreignId('dropshipping_product_id')->constrained('dropshipping_products')->onDelete('restrict');
            $table->string('sku')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_cost_price', 10, 2); // CJ unit price
            $table->decimal('unit_selling_price', 10, 2); // Our selling price to customer
            $table->decimal('total_cost_price', 10, 2); // quantity * unit_cost_price
            $table->decimal('total_selling_price', 10, 2); // quantity * unit_selling_price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropshipping_order_items');
    }
};
