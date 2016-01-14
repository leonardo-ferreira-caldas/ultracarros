<?php

namespace App\Repositories;

use App\Model\Carroceria;

class CarroceriaRepository extends AbstractRepository {

    protected $model;

    public function __construct(Carroceria $model) {
        $this->model = $model;
    }

}