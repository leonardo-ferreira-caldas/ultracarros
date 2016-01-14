<?php

use Illuminate\Database\Seeder;

class CarroCombustivelSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('combustivel')->insert([
            ['descricao' => 'Álcool'],
            ['descricao' => 'Álcool e gás natural'],
            ['descricao' => 'Diesel'],
            ['descricao' => 'Gás natural'],
            ['descricao' => 'Gasolina'],
            ['descricao' => 'Gasolina e álcool'],
            ['descricao' => 'Gasolina e elétrico'],
            ['descricao' => 'Gasolina e gás natural'],
            ['descricao' => 'Gasolina, álcool e gás natural'],
            ['descricao' => 'Gasolina, álcool, gás natural e benzina']
        ]);
    }
}
