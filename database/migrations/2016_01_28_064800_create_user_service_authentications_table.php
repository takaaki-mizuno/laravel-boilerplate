<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserServiceAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_service_authentications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id');

            $table->string('name');
            $table->string('email');

            $table->string('service');
            $table->bigInteger('service_id');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE user_service_authentications MODIFY created_at '.'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');

        DB::statement('ALTER TABLE user_service_authentications MODIFY updated_at '.'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_service_authentications');
    }
}
