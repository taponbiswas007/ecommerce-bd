<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('status');
            $table->string('previous_status')->nullable();
            $table->text('notes')->nullable();
            $table->string('document_path')->nullable(); // For delivery documents
            $table->string('document_name')->nullable(); // Original document name
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who made the change
            $table->string('location')->nullable(); // Current location for shipped status
            $table->timestamp('status_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
