<?php

use Illuminate\Database\Seeder;

class CarroTipoAnuncianteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_anunciante')->insert([
            ['descricao' => 'Concessionária'],
            ['descricao' => 'Loja'],
            ['descricao' => 'Particular']
        ]);
    }
}
