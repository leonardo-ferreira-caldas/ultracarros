<?php

namespace App\Repositories;

use App\Model\Opcional;

class OpcionalRepository extends AbstractRepository {

    protected $model;

    public function __construct(Opcional $model) {
        $this->model = $model;
    }

}