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
        Schema::create('product_tax_overrides', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->comment('Product ID');

            // VAT Override
            $table->boolean('override_vat')->default(false)->comment('Override default VAT for this product');
            $table->decimal('vat_percentage', 8, 2)->nullable()->comment('Custom VAT percentage for this product');
            $table->boolean('vat_included_in_price')->nullable()->comment('Whether VAT is included in price (null = use global setting)');

            // AIT Override
            $table->boolean('override_ait')->default(false)->comment('Override default AIT for this product');
            $table->decimal('ait_percentage', 8, 2)->nullable()->comment('Custom AIT percentage for this product');
            $table->boolean('ait_included_in_price')->nullable()->comment('Whether AIT is included in price (null = use global setting)');

            // Exemptions
            $table->boolean('vat_exempt')->default(false)->comment('Product is exempt from VAT');
            $table->boolean('ait_exempt')->default(false)->comment('Product is exempt from AIT');

            // Metadata
            $table->text('reason')->nullable()->comment('Reason for override (e.g., Essential commodity, Export product)');
            $table->timestamp('effective_from')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('When override becomes effective');
            $table->timestamp('effective_until')->nullable()->comment('When override expires');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('product_id');
            $table->index('override_vat');
            $table->index('override_ait');
            $table->index('effective_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tax_overrides');
    }
};
