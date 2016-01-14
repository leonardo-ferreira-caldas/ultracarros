<?php

namespace App\Http\Controllers;

use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuscaController extends Controller
{
    private $carro;

    public function __construct(CarroRepository $carro) {
        $this->carro = $carro;
    }

    public function buscar(Request $request) {
        return $this->carro->buscar($request->all());
    }
}
