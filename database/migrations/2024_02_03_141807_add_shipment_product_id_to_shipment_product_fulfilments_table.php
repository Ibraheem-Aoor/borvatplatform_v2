<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipment_product_fulfilments', function (Blueprint $table) {
            $table->unsignedBigInteger('shipment_product_id');
            $table->foreign('shipment_product_id')->references('id')->on('shipment_products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_product_fulfilments', function (Blueprint $table) {
            $table->dropColumn(['shipment_product_id']);
        });
    }
};
