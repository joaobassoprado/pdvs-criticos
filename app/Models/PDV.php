<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDV extends Model
{
    protected $table = 'p_d_v_s';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nome',
        'nome_fantasia',
    ];
}
