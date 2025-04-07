<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estagiario extends Model
{
    protected $fillable = ['nome', 'email', 'idade'];
}
