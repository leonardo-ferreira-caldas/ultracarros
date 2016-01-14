<?php

namespace App\Repositories;

use App\Model\Modelo;

class ModeloRepository extends AbstractRepository {

    protected $model;

    public function __construct(Modelo $model) {
        $this->model = $model;
    }

}