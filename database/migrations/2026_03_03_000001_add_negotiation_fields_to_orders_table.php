<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('negotiation_status')->default('open')->after('order_status');
            $table->decimal('additional_transport_cost', 10, 2)->default(0)->after('negotiation_status');
            $table->decimal('additional_carrying_cost', 10, 2)->default(0)->after('additional_transport_cost');
            $table->decimal('bank_transfer_cost', 10, 2)->default(0)->after('additional_carrying_cost');
            $table->decimal('additional_other_cost', 10, 2)->default(0)->after('bank_transfer_cost');
            $table->decimal('admin_discount_amount', 10, 2)->default(0)->after('additional_other_cost');
            $table->decimal('negotiated_total_amount', 10, 2)->nullable()->after('admin_discount_amount');
            $table->text('payment_instructions')->nullable()->after('negotiated_total_amount');
            $table->string('payment_reference')->nullable()->after('payment_instructions');
            $table->string('payment_proof_path')->nullable()->after('payment_reference');
            $table->boolean('is_self_delivery_risk')->default(false)->after('payment_proof_path');
            $table->timestamp('negotiation_updated_at')->nullable()->after('is_self_delivery_risk');
            $table->foreignId('quoted_by_admin_id')->nullable()->after('negotiation_updated_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['quoted_by_admin_id']);
            $table->dropColumn([
                'negotiation_status',
                'additional_transport_cost',
                'additional_carrying_cost',
                'bank_transfer_cost',
                'additional_other_cost',
                'admin_discount_amount',
                'negotiated_total_amount',
                'payment_instructions',
                'payment_reference',
                'payment_proof_path',
                'is_self_delivery_risk',
                'negotiation_updated_at',
                'quoted_by_admin_id',
            ]);
        });
    }
};
