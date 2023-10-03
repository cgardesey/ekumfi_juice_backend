<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesaler_carts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('wholesaler_cart_id')->unique();
            $table->string('order_id');
            $table->string('wholesaler_id');
            $table->string('agent_id');
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->boolean('delivered')->default(false);
            $table->boolean('paid')->default(false);
            $table->boolean('credited')->default(false);
            $table->boolean('credit_paid')->default(false);

            $table->foreign('wholesaler_id')->references('wholesaler_id')->on('wholesalers')->onDelete('cascade');
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
        Schema::dropIfExists('wholesaler_carts');
    }
}
