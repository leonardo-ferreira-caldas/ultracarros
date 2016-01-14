<?php

use Illuminate\Database\Seeder;

class EstadoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estado')->insert([
            ["id_estado" => "AC", "nome_estado" => "Acre"],
            ["id_estado" => "AL", "nome_estado" => "Alagoas"],
            ["id_estado" => "AM", "nome_estado" => "Amazonas"],
            ["id_estado" => "AP", "nome_estado" => "Amapá"],
            ["id_estado" => "BA", "nome_estado" => "Bahia"],
            ["id_estado" => "CE", "nome_estado" => "Ceará"],
            ["id_estado" => "DF", "nome_estado" => "Distrito Federal"],
            ["id_estado" => "ES", "nome_estado" => "Espírito Santo"],
            ["id_estado" => "GO", "nome_estado" => "Goiás"],
            ["id_estado" => "MA", "nome_estado" => "Maranhão"],
            ["id_estado" => "MT", "nome_estado" => "Mato Grosso"],
            ["id_estado" => "MS", "nome_estado" => "Mato Grosso do Sul"],
            ["id_estado" => "MG", "nome_estado" => "Minas Gerais"],
            ["id_estado" => "PA", "nome_estado" => "Pará"],
            ["id_estado" => "PB", "nome_estado" => "Paraíba"],
            ["id_estado" => "PR", "nome_estado" => "Paraná"],
            ["id_estado" => "PE", "nome_estado" => "Pernambuco"],
            ["id_estado" => "PI", "nome_estado" => "Piauí"],
            ["id_estado" => "RJ", "nome_estado" => "Rio de Janeiro"],
            ["id_estado" => "RN", "nome_estado" => "Rio Grande do Norte"],
            ["id_estado" => "RO", "nome_estado" => "Rondônia"],
            ["id_estado" => "RS", "nome_estado" => "Rio Grande do Sul"],
            ["id_estado" => "RR", "nome_estado" => "Roraima"],
            ["id_estado" => "SC", "nome_estado" => "Santa Catarina"],
            ["id_estado" => "SE", "nome_estado" => "Sergipe"],
            ["id_estado" => "SP", "nome_estado" => "São Paulo"],
            ["id_estado" => "TO", "nome_estado" => "Tocantins"]
        ]);
    }
}
