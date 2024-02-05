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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->string('order_item_id');
            $table->boolean('cancellation_request')->default(false);
            $table->string('offer_id')->nullable();
            $table->integer('quantity');
            $table->integer('quantity_shipped');
            $table->integer('quantity_cancelled');
            $table->double('unit_price')->nullable();
            $table->double('commission')->nullable();
            $table->enum('fulfilment_method' , ['FBR' , 'FBB']);
            $table->string('fulfilment_status');
            $table->dateTime('latest_changed_date_time')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
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
        Schema::dropIfExists('order_products');
    }
};
