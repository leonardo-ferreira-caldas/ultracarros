<?php

namespace App\Repositories;

use App\Model\Carro;

class CarroRepository {

    private $carro;

    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }

    public function buscar($searchInput) {

        $query = $this->carro
            ->whereNotNull('foto_capa')
            ->select('id_carro', 'nome_carro', 'kilometragem', 'preco', 'ano', 'foto_capa');

        $count = $query->count();

        $page = $searchInput['page'] ?? 1;
        $skip = ($page * 12) - 12;

        return [
            'total'        => $count,
            'rows'         => $query->skip($skip)->take(12)->orderBy('id_carro', 'asc')->get(),
            'current_page' => $page,
            'total_pages'  => ceil($count / 12)
        ];

    }

}