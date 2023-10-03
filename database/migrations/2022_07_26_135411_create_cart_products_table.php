<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('cart_product_id')->unique();
            $table->string('cart_id')->nullable();
            $table->string('seller_product_id');
            $table->integer('quantity')->default(0);
            $table->decimal('price', 8, 2)->default(0);

            $table->unique(['cart_id', 'seller_product_id']);

            $table->foreign('cart_id')->references('cart_id')->on('carts')->onDelete('cascade');
            $table->foreign('seller_product_id')->references('seller_product_id')->on('seller_products')->onDelete('cascade');

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
        Schema::dropIfExists('cart_products');
    }
}
