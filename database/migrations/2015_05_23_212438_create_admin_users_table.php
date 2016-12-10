<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('email');
            $table->string('password', 60);

            $table->string('locale')->default('');

            $table->bigInteger('last_notification_id')->default(0);

            $table->string('api_access_token')->default('');

            $table->unsignedBigInteger('profile_image_id')->default(0);

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('admin_users', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
