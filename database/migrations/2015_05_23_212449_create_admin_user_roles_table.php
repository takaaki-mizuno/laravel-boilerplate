<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_user_roles', function(Blueprint $table)
		{
			$table->bigInteger('admin_user_id');
			$table->string('role');
			$table->timestamps();
			$table->primary(['admin_user_id','role']);
		});
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
