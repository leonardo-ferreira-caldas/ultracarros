<?php

namespace App\Mappers;

use App\Model\Combustivel;

class RepositoryMapper {

    public function cor() {
        return app('App\Repositories\CorRepository');
    }

    public function cambio() {
        return app('App\Repositories\CambioRepository');
    }

    public function marca() {
        return app('App\Repositories\MarcaRepository');
    }

    public function modelo() {
        return app('App\Repositories\ModeloRepository');
    }

    public function combustivel() {
        return app('App\Repositories\CombustivelRepository');
    }

    public function carroceria() {
        return app('App\Repositories\CarroceriaRepository');
    }

    public function opcional() {
        return app('App\Repositories\OpcionalRepository');
    }

    public function versao() {
        return app('App\Repositories\VersaoRepository');
    }

    public function documentacao() {
        return app('App\Repositories\DocumentacaoRepository');
    }

}