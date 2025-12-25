<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packaging_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('unit_name'); // e.g., "Roll", "Cartoon", "Loose"
            $table->decimal('units_per', 12, 4)->comment('How many product sales units per this shipping unit, e.g., 1 Roll = 10 KG');
            $table->integer('priority')->default(10)->comment('Higher priority rules applied first (larger units)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packaging_rules');
    }
};
