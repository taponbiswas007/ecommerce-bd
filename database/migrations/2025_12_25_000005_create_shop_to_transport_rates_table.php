<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shop_to_transport_rates', function (Blueprint $table) {
            $table->id();
            $table->string('package_type', 30); // e.g., "Cartoon", "Roll", "Loose"
            $table->string('district', 50)->nullable();
            $table->string('upazila', 50)->nullable();
            $table->decimal('rate', 12, 2); // cost per package
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['package_type', 'district', 'upazila'], 'shop_transport_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_to_transport_rates');
    }
};
