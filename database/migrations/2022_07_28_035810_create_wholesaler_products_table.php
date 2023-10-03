<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesaler_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('wholesaler_product_id')->unique();
            $table->string('product_id');
            $table->string('wholesaler_id');
            $table->integer('unit_quantity')->default(1);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->integer('quantity_available')->default(0);

            $table->unique(['product_id', 'wholesaler_id']);

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('wholesaler_id')->references('wholesaler_id')->on('wholesalers')->onDelete('cascade');

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
        Schema::dropIfExists('wholesaler_products');
    }
}
