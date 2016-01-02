<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CarroCrawlerMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carro_crawler', function (Blueprint $table) {
            $table->increments('id_carro_crawler');
            $table->unsignedInteger('fk_carro');
            $table->string('url');
            $table->dateTime('ultima_atualizacao')->defailt(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('ind_atualizando')->default(false);
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
        Schema::dropIfExists('carro_crawler');
    }
}
