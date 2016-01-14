<?php

namespace App\Repositories;

use App\Model\Marca;

class MarcaRepository extends AbstractRepository {

    protected $model;

    public function __construct(Marca $model) {
        $this->model = $model;
    }

}