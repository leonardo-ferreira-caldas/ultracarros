<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CarroMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carro', function (Blueprint $table) {
            $table->increments('id_carro');
            $table->string('nome_carro');
            $table->decimal('preco', 13, 2);
            $table->unsignedInteger('kilometragem');
            $table->unsignedTinyInteger('fk_cambio');
            $table->unsignedTinyInteger('fk_carroceria');
            $table->unsignedTinyInteger('fk_cor');
            $table->unsignedTinyInteger('fk_combustivel');
            $table->unsignedInteger('fk_montadora');
            $table->unsignedInteger('fk_modelo');
            $table->unsignedTinyInteger('fk_tipo_anunciante');
            $table->unsignedInteger('fk_cidade');
            $table->unsignedInteger('fk_versao');
            $table->unsignedTinyInteger('portas');
            $table->unsignedTinyInteger('final_placa');
            $table->unsignedSmallInteger('ano');
            $table->unsignedSmallInteger('modelo');
            $table->boolean('ind_aceita_troca')->default(0);
            $table->boolean('ind_blindagem')->default(0);
            $table->boolean('ind_veiculo_novo');
            $table->boolean('ind_ativo')->default(1);
            $table->date('data_anuncio');
            $table->text('descricao_anunciante');
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
        Schema::dropIfExists('carro');
    }
}
