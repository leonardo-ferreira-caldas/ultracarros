<?php

namespace App\Repositories;

use App\Model\Cambio;

class CambioRepository extends AbstractRepository {

    protected $model;

    public function __construct(Cambio $model) {
        $this->model = $model;
    }

}