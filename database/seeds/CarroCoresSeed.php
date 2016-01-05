<?php

use Illuminate\Database\Seeder;

class CarroCoresSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cor')->insert([
            ['descricao' => 'Amarelo'],
            ['descricao' => 'Azul'],
            ['descricao' => 'Bege'],
            ['descricao' => 'Branco'],
            ['descricao' => 'Bronze'],
            ['descricao' => 'Cinza'],
            ['descricao' => 'Dourado'],
            ['descricao' => 'Indefinida'],
            ['descricao' => 'Laranja'],
            ['descricao' => 'Marrom'],
            ['descricao' => 'Prata'],
            ['descricao' => 'Preto'],
            ['descricao' => 'Rosa'],
            ['descricao' => 'Roxo'],
            ['descricao' => 'Verde'],
            ['descricao' => 'Vermelho'],
            ['descricao' => 'Vinho']
        ]);
    }
}
