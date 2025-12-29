<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title','description','priority', 'empresa_id','motivo_id', 'created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];

     public function motivo(): HasOne
    {
        return $this->hasOne(related: Motivo::class, foreignKey: 'id', localKey: 'motivo_id');
    }

    public function empresa(): HasOne
    {
        return $this->hasOne(related: Empresa::class, foreignKey: 'id', localKey: 'empresa_id');
    }
}
