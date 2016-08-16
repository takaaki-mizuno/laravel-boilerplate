<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');

            $table->string('category_type');

            $table->string('type');
            $table->json('data');
            $table->text('content');

            $table->boolean('read')->default(false);
            $table->timestamp('sent_at');

            $table->timestamps();

            $table->index(['user_id', 'sent_at']);
            $table->index(['read', 'user_id', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
