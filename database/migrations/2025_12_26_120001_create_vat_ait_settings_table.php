<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vat_ait_settings', function (Blueprint $table) {
            $table->id();

            // VAT Settings
            $table->decimal('default_vat_percentage', 8, 2)->default(15.00)->comment('Default VAT percentage (e.g., 15%)');
            $table->boolean('vat_enabled')->default(true)->comment('Enable/disable VAT globally');
            $table->boolean('vat_included_in_price')->default(true)->comment('Whether VAT is included in displayed price');

            // AIT Settings
            $table->decimal('default_ait_percentage', 8, 2)->default(2.00)->comment('Default AIT percentage (e.g., 2%)');
            $table->boolean('ait_enabled')->default(true)->comment('Enable/disable AIT globally');
            $table->boolean('ait_included_in_price')->default(false)->comment('Whether AIT is included in displayed price');

            // AIT Exemption Categories (comma-separated category IDs)
            $table->text('ait_exempt_categories')->nullable()->comment('Category IDs exempt from AIT');

            // Metadata
            $table->text('notes')->nullable()->comment('Admin notes about tax settings');
            $table->timestamp('effective_from')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('When these settings become effective');
            $table->timestamps();

            // Soft delete for historical tracking
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_ait_settings');
    }
};
