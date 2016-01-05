<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class HomeController extends Controller
{
    public function teste() {

        $aadd = Storage::disk('s3')->put('teste.jpg', fopen(public_path('img/carros/8_568b1b1b6180f.jpg'), 'r+'), 'public');
        dd($aadd);
    }
}
