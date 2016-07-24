<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('url')->default('');
            $table->text('title')->nullable();

            $table->string('entity_type')->default("");
            $table->bigInteger('entity_id')->default(0);

            $table->boolean('is_local')->default(false);

            $table->unsignedInteger('file_category_type')->default(0);

            $table->string('s3_key')->default('');
            $table->string('s3_bucket')->default('');
            $table->string('s3_region')->default('');
            $table->string('s3_extension')->default('');

            $table->string('media_type')->default('');
            $table->string('format')->default('');

            $table->unsignedBigInteger('file_size')->default(0);

            $table->boolean('is_enabled')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['file_category_type', 'id', 'deleted_at']);
            $table->index(['id', 'deleted_at']);
            $table->index(['url', 'deleted_at']);
            $table->index(['entity_type', 'entity_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }

}
