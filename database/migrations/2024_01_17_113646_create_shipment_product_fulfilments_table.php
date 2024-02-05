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
        Schema::create('shipment_product_fulfilments', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('distribution_party');
            $table->date('latest_delivery_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('time_frame_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fulfilments');
    }
};
