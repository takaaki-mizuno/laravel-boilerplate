<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateAdminPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        $this->updateTimestampDefaultValue('admin_password_resets', [], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('admin_password_resets');
    }
}
