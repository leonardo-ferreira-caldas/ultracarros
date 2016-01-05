<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Crawler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->unique();
            $table->boolean('ind_crawled')->default("0");
            $table->unsignedTinyInteger('failed_tries')->default("0");
            $table->text('error_log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawler');
    }
}
