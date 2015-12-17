<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('url')->default('');
            $table->text('title')->nullable();

            $table->unsignedInteger('file_category')->default(0);
            $table->unsignedInteger('file_subcategory')->default(0);

            $table->string('s3_key')->default('');
            $table->string('s3_bucket')->default('');
            $table->string('s3_region')->default('');
            $table->string('s3_extension')->default('');

            $table->string('media_type')->default('');
            $table->string('format')->default('');

            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('width')->default(0);
            $table->unsignedInteger('height')->default(0);

            $table->boolean('has_alternative')->default(false);
            $table->string('alternative_media_type')->default('');
            $table->string('alternative_format')->default('');
            $table->string('alternative_extension')->default('');

            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index(['file_category', 'id', 'deleted_at']);
            $table->index(['id', 'deleted_at']);
            $table->index(['url', 'deleted_at']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
