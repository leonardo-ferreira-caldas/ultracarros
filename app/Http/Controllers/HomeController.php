<?php

namespace App\Http\Controllers;

use App\Mappers\RepositoryMapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class HomeController extends Controller
{
    private $repository;

    public function __construct(RepositoryMapper $mapper) {
        $this->repository = $mapper;
    }

    public function home() {
        return view('home', [
            'cambio' => $this->repository->cambio()->listar(),
            'cor' => $this->repository->cor()->listar(),
            'carroceria' => $this->repository->carroceria()->listar(),
            'modelo' => $this->repository->modelo()->listar(),
            'marca' => $this->repository->marca()->listar(),
            'opcional' => $this->repository->opcional()->listar(),
            'documentacao' => $this->repository->documentacao()->listar()
        ]);
    }

}
