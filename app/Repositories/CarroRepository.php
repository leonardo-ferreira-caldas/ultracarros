<?php

namespace App\Repositories;

use App\Model\Carro;

class CarroRepository {

    private $carro;

    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }

    public function buscar() {
        return $this->carro
            ->whereNotNull('foto_capa')
            ->select('id_carro', 'nome_carro', 'kilometragem', 'preco', 'ano', 'foto_capa')
            ->skip(0)
            ->take(12)
            ->get();
    }

}