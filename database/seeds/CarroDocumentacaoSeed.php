<?php

use Illuminate\Database\Seeder;

class CarroDocumentacaoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documentacao')->insert([
            ['descricao' => 'Alienado'],
            ['descricao' => 'Garantia de fábrica'],
            ['descricao' => 'IPVA pago'],
            ['descricao' => 'Licenciado'],
            ['descricao' => 'Todas as revisões feitas pela agenda do carro'],
            ['descricao' => 'Todas as revisões feitas pela concessionária'],
            ['descricao' => 'Único dono']
        ]);
    }
}
