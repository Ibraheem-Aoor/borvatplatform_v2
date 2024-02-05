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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('image')->nullable();
            $table->string('ean')->unique();
            $table->string('purchase_place')->nullable();
            $table->double('purchase_price')->nullable();
            $table->integer('num_of_sales')->default(0);
            $table->double('weight')->default(0);
            $table->double('width')->default(0);
            $table->double('length')->default(0);
            $table->double('height')->default(0);
            $table->integer('number_of_pieces')->nullable();
            $table->text('note')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('products');
    }
};
