<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('chat_id')->unique();
            $table->string('chat_ref_id')->nullable();
            $table->string('text')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_description')->nullable();
            $table->string('link_image')->nullable();
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->string('attachment_title')->nullable();
            $table->boolean('read_by_recipient')->default(false);
            $table->boolean('sent_by_consumer')->default(false);
            $table->string('sender_role')->nullable();
            $table->string('tag');
            $table->string('consumer_id')->nullable();;
            $table->string('seller_id')->nullable();
            $table->string('agent_id')->nullable();
            $table->string('wholesaler_id')->nullable();
            $table->string('ekumfi_info_id')->nullable();

            $table->foreign('consumer_id')->references('consumer_id')->on('consumers')->onDelete('cascade');
            $table->foreign('seller_id')->references('seller_id')->on('sellers')->onDelete('cascade');
            $table->foreign('agent_id')->references('agent_id')->on('agents')->onDelete('cascade');
            $table->foreign('wholesaler_id')->references('wholesaler_id')->on('wholesalers')->onDelete('cascade');
            $table->foreign('ekumfi_info_id')->references('ekumfi_info_id')->on('ekumfi_infos')->onDelete('cascade');

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
        Schema::dropIfExists('chats');
    }
}
