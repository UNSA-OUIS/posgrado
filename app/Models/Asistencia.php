<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $fillable = [
        'cui',
        'anho',
        'nues',
        'espe',
        'pension_asistio',
    ];

    protected $casts = [
        'pension_asistio' => 'array'
    ];
}
