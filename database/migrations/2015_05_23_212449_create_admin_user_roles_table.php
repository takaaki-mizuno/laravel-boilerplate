<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
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

        DB::statement("ALTER TABLE admin_user_roles MODIFY created_at " .
            "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

        DB::statement("ALTER TABLE admin_user_roles MODIFY updated_at " .
            "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_user_roles');
    }

}
