<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transport_company_id')->constrained('transport_companies')->onDelete('cascade');
            $table->string('package_type', 30); // e.g., "Cartoon", "Roll", "Loose"
            $table->string('district', 50)->nullable();
            $table->string('upazila', 50)->nullable();
            $table->decimal('rate', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['transport_company_id', 'district', 'upazila', 'package_type'], 'pkg_rate_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_rates');
    }
};
