<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('user_id')->unique();
            $table->string('phone_number')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token')->unique();
            $table->string('role')->nullable();
            $table->integer('email_verified')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('connected')->default(false);
            $table->string('otp')->nullable();
            $table->string('app_hash')->nullable();
            $table->string('os_version')->nullable();
            $table->string('sdk_version')->nullable();
            $table->string('device')->nullable();
            $table->string('device_model')->nullable();
            $table->string('device_product')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('android_id')->nullable();
            $table->string('version_release')->nullable();
            $table->string('device_height')->nullable();
            $table->string('device_width')->nullable();
            $table->string('guid')->nullable();

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
        Schema::dropIfExists('users');
    }
}
