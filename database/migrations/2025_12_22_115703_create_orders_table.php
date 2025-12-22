<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'ready_to_ship',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();
            $table->string('shipping_district');
            $table->string('shipping_upazila');
            $table->text('shipping_address');
            $table->string('transport_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('delivery_document')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
