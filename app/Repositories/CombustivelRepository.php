<?php

namespace App\Repositories;

use App\Model\Combustivel;

class CombustivelRepository extends AbstractRepository {

    protected $model;

    public function __construct(Combustivel $model) {
        $this->model = $model;
    }

}