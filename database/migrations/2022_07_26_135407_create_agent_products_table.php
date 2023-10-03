<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('agent_product_id')->unique();
            $table->string('product_id');
            $table->string('agent_id');
            $table->integer('unit_quantity')->default(1);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->integer('quantity_available')->default(0);

            $table->unique(['product_id', 'agent_id']);

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('agent_id')->references('agent_id')->on('agents')->onDelete('cascade');

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
        Schema::dropIfExists('agent_products');
    }
}
