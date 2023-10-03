<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('payment_id')->unique();
            $table->string('msisdn')->nullable();
            $table->string('country_code')->nullable();
            $table->string('network')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('message')->nullable();
            $table->string('response_message')->nullable();
            $table->string('status')->nullable();
            $table->string('external_reference_no')->nullable();
            $table->string('transaction_status_reason')->nullable();
            $table->string('cart_id')->nullable();
            $table->string('stock_cart_id')->nullable();
            $table->string('wholesaler_cart_id')->nullable();

            $table->foreign('cart_id')->references('cart_id')->on('carts')->onDelete('cascade');
            $table->foreign('stock_cart_id')->references('stock_cart_id')->on('stock_carts')->onDelete('cascade');
            $table->foreign('wholesaler_cart_id')->references('wholesaler_cart_id')->on('wholesaler_carts')->onDelete('cascade');

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
        Schema::dropIfExists('payments');
    }
}
