<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Programa extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'actescu';
    protected $primaryKey = 'nues';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'area',
        'facu',
        'nive',
        'nues',
        'escu',
        'espe',
        'nesc',
        'fin',
        'ffin',
        'napr',
        'flc',
        'fln',
        'hom',
        'muj',
        'ctes',
        'ninv',
        'nnor',
        'nseg',
        'nter',
        'ncua',
        'total',
        'cegre',
    ];

    //protected $with = ["especialidad"];

    /*public function alumno_escuelas()
    {
        return $this->hasMany(AlumnoEscuela::class, 'nues', 'nues');
    }*/

    /**
     * Get one of the especialidad associated with the Escuela
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function especialidad(): HasOne
    {
        return $this->hasOne(Especialidad::class, 'nues', 'nues');
    }

    /**
     * Get all of the especialidades for the Escuela
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function especialidades(): HasMany
    {
        return $this->hasMany(Especialidad::class, 'nues', 'nues')
            ->whereRaw('actespe.plan = (select max(plan) from actespe where nues = ?)', [$this->nues]);
    }

    /*public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facu', 'facu');
    }*/
    public function pensiones()
    {
        return $this->hasMany(ProgramaPension::class, 'nues', 'nues');
    }
}
