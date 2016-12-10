<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

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

        $this->updateTimestampDefaultValue('user_service_authentications', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_service_authentications');
    }
}
