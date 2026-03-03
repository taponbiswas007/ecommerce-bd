<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('vat_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('ait_amount', 10, 2)->default(0)->after('vat_amount');
        });

        DB::table('orders')->update([
            'vat_amount' => DB::raw('0'),
            'ait_amount' => DB::raw('tax_amount'),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['vat_amount', 'ait_amount']);
        });
    }
};
