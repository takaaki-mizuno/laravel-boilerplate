<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('');
            $table->string('email');
            $table->string('password', 60);

            $table->smallInteger('gender')->default(1);     // 1 = Male, 0 = Female
            $table->string('telephone')->nullable()->default('');
            $table->date('birthday')->nullable()->default(null);
            $table->string('locale')->nullable()->default('');
            $table->string('address')->nullable()->default('');

            $table->bigInteger('last_notification_id')->default(0);

            $table->string('api_access_token')->default('');

            $table->bigInteger('profile_image_id')->default(0);

            $table->smallInteger('is_activated')->default(1);

            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('users', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
