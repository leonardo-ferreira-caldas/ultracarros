<?php

namespace App\Repositories;

use App\Model\Versao;

class VersaoRepository extends AbstractRepository {

    protected $model;

    public function __construct(Versao $model) {
        $this->model = $model;
    }

}