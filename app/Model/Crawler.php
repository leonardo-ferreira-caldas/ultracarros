<?php

namespace App\Model;

use Jenssegers\Mongodb\Model as Eloquent;

class Crawler extends Eloquent
{
    protected $collection = 'crawler';
    protected $fillable = ['url'];
}
