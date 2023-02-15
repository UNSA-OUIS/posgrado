<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Denuncia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'denuncias';

    protected $fillable = ['id', 'administrado_id', 'administrador_id', 'codigo', 'descripcion', 'respuesta', 'estado'];

    public function administrado()
    {
        return $this->belongsTo(User::class, 'administrado_id');
    }
}
