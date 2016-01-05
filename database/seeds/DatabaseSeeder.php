<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CarroCambioSeed::class);
        $this->call(CarroCarroceriaSeed::class);
        $this->call(CarroCombustivelSeed::class);
        $this->call(CarroCoresSeed::class);
        $this->call(CarroDocumentacaoSeed::class);
        $this->call(CarroModeloSeed::class);
        $this->call(CarroMontadoraSeed::class);
        $this->call(CarroOpcionaisSeed::class);
        $this->call(CarroTipoAnuncianteSeed::class);
        $this->call(EstadoSeed::class);
        $this->call(CidadeSeed::class);
    }
}
