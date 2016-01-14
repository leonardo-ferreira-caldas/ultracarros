<?php

namespace App\Repositories;

use App\Model\Cor;

class CorRepository extends AbstractRepository {

    protected $model;

    public function __construct(Cor $model) {
        $this->model = $model;
    }

}