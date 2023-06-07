<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrograma extends Model
{
    protected $fillable = [
        'user_id',
        'nues',
        'espe'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programa()
    {
        return $this->setConnection('mysql2')->belongsTo(Programa::class, 'nues', 'nues');
    }

    public function especialidad()
    {
        return $this->setConnection('mysql2')->belongsTo(Especialidad::class, 'espe', 'numesp')
            ->where('nues', '=', $this->nues)
            ->whereRaw('plan = (select max(plan) from actespe where nues = ?)', [$this->nues]);
    }
}
