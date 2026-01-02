<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'responsavel', 'pdv_id', 'pergunta_id', 'resposta'];

    public function pdv()
    {
        return $this->belongsTo(PDV::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
    
}
