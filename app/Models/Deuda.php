<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deuda extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'bancovirtual';

    protected $fillable = [
        'customerId',
        'customerName',
        'serviceId',
        'app_id',
        'paymentDetail',
        'amountCalculated',
        'amount',
        'amountCanceled',
        'status',
        'documentReference',
        't_usuario',
        'cui',
        'nues',
        'espe',
        'tregistro',
        'flag',
        'documentId'
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
