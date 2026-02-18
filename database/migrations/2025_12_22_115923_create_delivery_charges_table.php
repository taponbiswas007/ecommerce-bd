<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->string('district', 100);
            $table->string('upazila', 100);
            $table->decimal('charge', 10, 2);
            $table->integer('estimated_days')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['district', 'upazila']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_charges');
    }
};
