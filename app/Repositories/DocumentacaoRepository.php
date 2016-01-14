<?php

namespace App\Repositories;

use App\Model\Documentacao;

class DocumentacaoRepository extends AbstractRepository {

    protected $model;

    public function __construct(Documentacao $model) {
        $this->model = $model;
    }

}