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
        Schema::create('dropshipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        \Illuminate\Support\Facades\DB::table('dropshipping_settings')->insert([
            ['key' => 'cj_api_key', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cj_api_secret', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cj_api_url', 'value' => 'https://api.cjdropshipping.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'enable_dropshipping', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'auto_confirm_orders', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_profit_margin_percent', 'value' => '20', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropshipping_settings');
    }
};
