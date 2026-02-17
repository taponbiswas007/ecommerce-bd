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
        Schema::create('dropshipping_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('cj_order_number')->unique();
            $table->string('cj_order_status')->default('pending'); // pending, confirmed, processing, shipped, delivered
            $table->decimal('cost_price', 10, 2); // What we pay to CJ
            $table->decimal('selling_price', 10, 2); // What customer paid us
            $table->decimal('profit', 10, 2)->nullable(); // selling_price - cost_price
            $table->string('tracking_number')->nullable();
            $table->text('shipping_info')->nullable(); // JSON
            $table->text('cj_response_data')->nullable(); // JSON - Full CJ API response
            $table->timestamp('submitted_to_cj_at')->nullable();
            $table->timestamp('confirmed_by_cj_at')->nullable();
            $table->timestamp('shipped_by_cj_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropshipping_orders');
    }
};
