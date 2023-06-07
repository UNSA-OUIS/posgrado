<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramaPension extends Model
{
    protected $connection = "mysql2";
    protected $table = 'SIAC_MONTOS_POSG';

    protected $fillable = [
        'id',
        'anho',
        'facu',
        'nues',
        'espe',
        'monto',
        'cant_pens',
        'nro_pens',
        'monto_extr',
        'total',
        'obs'
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'nues', 'nues');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'espe', 'numesp')
            ->where('nues', '=', $this->nues)
            ->whereRaw('plan = (select max(plan) from actespe where nues = ?)', [$this->nues]);
    }
}
