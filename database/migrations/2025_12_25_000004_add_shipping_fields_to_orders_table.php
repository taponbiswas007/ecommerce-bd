<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('transport_company_id')->nullable()->constrained('transport_companies')->nullOnDelete();
            $table->string('shipping_method')->default('transport')->comment("'transport'|'own'|'pickup'");
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['transport_company_id']);
            $table->dropColumn('transport_company_id');
            $table->dropColumn('shipping_method');
        });
    }
};
