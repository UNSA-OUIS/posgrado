<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $connection = "mysql2";
    protected $table = 'acdidal';
    protected $primaryKey = 'cui';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /* protected $fillable = [
        'nues',
        'admision_area',
        'estado_suficiencia',
        'estado_especialidad',
        'estado',
        'created_at',
        'updated_at',
        'nive'
    ];

    public function escuela_dufa()
    {        
        return $this->belongsTo(Escuela::class, 'nues', 'nues');
    }

    public function proceso_escuelas()
    {        
        return $this->hasMany(ProcesoEscuela::class, 'nues', 'nues');
    }*/
}
