<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Update all NULL attributes_hash to empty string
        DB::table('carts')->whereNull('attributes_hash')->update(['attributes_hash' => '']);
        // Change column to NOT NULL with default ''
        Schema::table('carts', function (Blueprint $table) {
            $table->string('attributes_hash')->default('')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('attributes_hash')->nullable()->default(null)->change();
        });
    }
};
