<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('slug')->default('');
            $table->text('title')->nullable();

            $table->text('keywords')->nullable();
            $table->text('description')->nullable();

            $table->mediumText('content')->nullable();

            $table->bigInteger('cover_image_id')->default(0);

            $table->string('locale')->default('ja');

            $table->boolean('is_enabled')->default(true);
            $table->timestamp('publish_started_at')->default('2000-01-01 00:00:00');
            $table->timestamp('publish_ended_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index(['is_enabled', 'publish_started_at', 'publish_ended_at', 'id']);

        });

        $this->updateTimestampDefaultValue('articles', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
