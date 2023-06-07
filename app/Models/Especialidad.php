<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Especialidad extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'actespe';
    //protected $primaryKey = '';
    public $incrementing = false;
    //protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nues',
        'numesp',
        'nomesp',
        'plan'
    ];

    /**
     * Get the escuela that owns the Especialidad
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'nues', 'nues');
    }
}
