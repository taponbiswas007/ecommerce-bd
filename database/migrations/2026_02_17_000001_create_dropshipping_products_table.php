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
        Schema::create('dropshipping_products', function (Blueprint $table) {
            $table->id();
            $table->string('cj_product_id')->unique(); // CJ product ID
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('profit_margin', 10, 2)->default(0); // Selling price - Cost price
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->text('image_url')->nullable();
            $table->string('sku')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('minimum_order_quantity')->default(1);
            $table->text('product_attributes')->nullable(); // JSON
            $table->text('shipping_info')->nullable(); // JSON with shipping details
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('cj_response_data')->nullable(); // Store full CJ API response
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropshipping_products');
    }
};
