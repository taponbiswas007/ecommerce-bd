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
        Schema::create('dropshipping_api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('response_code')->nullable();
            $table->boolean('success');
            $table->text('error_message')->nullable();
            $table->string('related_type')->nullable(); // order, product, etc.
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();

            $table->index('endpoint');
            $table->index('success');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropshipping_api_logs');
    }
};
