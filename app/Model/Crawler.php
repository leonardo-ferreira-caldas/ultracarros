<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $table = "crawler";
    protected $primaryKey = 'id';
    protected $fillable = ['url'];
}
