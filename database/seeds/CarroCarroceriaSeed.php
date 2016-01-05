<?php

use Illuminate\Database\Seeder;

class CarroCarroceriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carroceria')->insert([
            ['descricao' => 'Buggy'],
            ['descricao' => 'Conversível'],
            ['descricao' => 'Cupê'],
            ['descricao' => 'Hatchback'],
            ['descricao' => 'Minivan'],
            ['descricao' => 'Perua/SW'],
            ['descricao' => 'Picape'],
            ['descricao' => 'Sedã'],
            ['descricao' => 'SUV'],
            ['descricao' => 'Van/Utilitário']
        ]);
    }
}
