<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateSiteConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
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

        $this->updateTimestampDefaultValue('site_configurations', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('site_configurations');
    }
}
