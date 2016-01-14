<?php

use Illuminate\Database\Seeder;

class CarroCambioSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cambio')->insert([
            ['descricao' => 'Manual'],
            ['descricao' => 'Automática'],
            ['descricao' => 'Automática Sequencial'],
            ['descricao' => 'CVT'],
            ['descricao' => 'Semi-automática']
        ]);
    }
}
