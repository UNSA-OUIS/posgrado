<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uniforme extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'orden',
        'dni',
        'apn',
        'tipo_personal',
        'area',
        'recogido',
        'user_id',
        'mesa'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
