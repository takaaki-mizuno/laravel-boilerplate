<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->index(['category_type', 'user_id', 'locale', 'sent_at'], 'category_index');
            $table->index(['type', 'user_id', 'locale', 'sent_at']);
            $table->index(['user_id', 'locale', 'sent_at']);
            $table->index(['read', 'user_id', 'locale', 'sent_at']);
        });

        DB::statement('ALTER TABLE user_notifications MODIFY sent_at '.'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        DB::statement('ALTER TABLE user_notifications MODIFY created_at '.'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        DB::statement('ALTER TABLE user_notifications MODIFY updated_at '.'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        \Schema::dropIfExists('user_notifications');
    }
}
