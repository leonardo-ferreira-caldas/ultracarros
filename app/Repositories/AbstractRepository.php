<?php

namespace App\Repositories;

abstract class AbstractRepository {

    public function listar() {
        return $this->model->all();
    }

}