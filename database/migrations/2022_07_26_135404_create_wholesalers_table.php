<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('wholesaler_id')->unique();
            $table->string('confirmation_token')->nullable();

            $table->string('shop_name')->nullable();
            $table->text('shop_image_url')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('auxiliary_contact')->nullable();
            $table->string('momo_number')->nullable();
            $table->double('longitude', 20, 15)->default(0);
            $table->double('latitude', 20, 15)->default(0);
            $table->string('digital_address')->nullable();
            $table->string('street_address')->nullable();
            $table->string('identification_type')->nullable();
            $table->string('identification_number')->nullable();
            $table->text('identification_image_url')->nullable();
            $table->string('availability')->default("Available");
            $table->boolean('verified')->default(false);

            $table->string('user_id')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('wholesalers');
    }
}
