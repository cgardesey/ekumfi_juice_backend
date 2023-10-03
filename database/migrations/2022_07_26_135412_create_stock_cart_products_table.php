<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_cart_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('stock_cart_product_id')->unique();
            $table->string('stock_cart_id');
            $table->string('agent_product_id');
            $table->integer('quantity')->default(0);
            $table->decimal('price', 8, 2)->default(0);

            $table->unique(['stock_cart_id', 'agent_product_id']);

            $table->foreign('stock_cart_id')->references('stock_cart_id')->on('stock_carts')->onDelete('cascade');
            $table->foreign('agent_product_id')->references('agent_product_id')->on('agent_products')->onDelete('cascade');

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
        Schema::dropIfExists('stock_cart_products');
    }
}
