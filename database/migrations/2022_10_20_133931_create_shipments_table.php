<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique(); //shipmentId
            $table->string('first_name');
            $table->string('surname');
            $table->string('city');
            $table->string('house_no');
            $table->string('street_name');
            $table->string('zip_code');
            $table->string('email')->nullable();
            $table->dateTime('place_date');
            $table->boolean('pickup_point')->default(false);
            $table->longText('shipment_details');
            $table->longText('billing_details');
            $table->longText('items');
            $table->longText('transport');
            $table->string('country_code');
            $table->text('note')->nullable();
            $table->boolean('has_label')->default(false);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
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
        Schema::dropIfExists('shipments');
    }
};
