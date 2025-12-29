<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    use HasFactory, SoftDeletes;
     
    protected $fillable = ['name', 'empresa_id', 'created_by', 'updated_by', 'deleted_by', 'deleted_at'];

    public function empresa(): HasOne
    {
        return $this->hasOne(related: Empresa::class, foreignKey: 'id', localKey: 'empresa_id');
    }


}
