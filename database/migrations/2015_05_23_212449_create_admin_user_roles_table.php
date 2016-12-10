<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateAdminUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('admin_user_id');
            $table->string('role');

            $table->timestamps();

            $table->index(['admin_user_id']);
            $table->index(['role', 'admin_user_id']);
        });

        $this->updateTimestampDefaultValue('admin_user_roles', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_roles');
    }
}
