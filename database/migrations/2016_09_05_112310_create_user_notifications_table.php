<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->default(0);

            $table->string('category_type')->default(0);

            $table->string('type')->default('');
            $table->text('data')->nullable();
            $table->text('content')->default('');

            $table->string('locale')->default('');

            $table->boolean('read')->default(false);
            $table->timestamp('sent_at');

            $table->timestamps();

            $table->index(['category_type', 'user_id', 'locale', 'sent_at'], 'category_user_index');
            $table->index(['type', 'user_id', 'locale', 'sent_at']);
            $table->index(['user_id', 'locale', 'sent_at']);
            $table->index(['read', 'user_id', 'locale', 'sent_at']);
        });

        $this->updateTimestampDefaultValue('user_notifications', ['updated_at'], ['sent_at', 'created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        \Schema::dropIfExists('user_notifications');
    }
}
