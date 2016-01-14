<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'montadora';
    protected $primaryKey = 'id_montadora';
    public $timestamps = false;
}
