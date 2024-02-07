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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('api_id'); //orderId
            $table->boolean('pickup_point')->default(false);
            $table->dateTime('place_date');
            $table->longText('shipment_details')->nullable();
            $table->longText('billing_details')->nullable();
            $table->longText('order_items')->nullable();
            $table->boolean('is_shipped_by_dashboard')->default(false)->comment('To Determine That This Order Has A Shipment');
            $table->string('country_code')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
