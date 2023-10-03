<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('agent_id')->unique();
            $table->string('confirmation_token')->nullable();
            $table->string('agent_type')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_names')->nullable();
            $table->string('gender')->nullable();
            $table->text('profile_image_url')->nullable();
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
            $table->string('wholesaler_id')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('agents');
    }
}
