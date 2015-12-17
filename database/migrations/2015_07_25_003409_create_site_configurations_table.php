<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->string('name')->default('');

            $table->string('title')->default('');

            $table->text('keywords')->nullable();
            $table->text('description')->nullable();

            $table->bigInteger('ogp_image_id')->default(0);
            $table->bigInteger('twitter_card_image_id')->default(0);

            $table->timestamps();

            $table->index(['locale']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_configurations');
    }
}
