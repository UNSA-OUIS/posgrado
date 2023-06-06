<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AsistenciaController extends Controller
{
    public function vista()
    {
        //$this->authorize("viewAny", Denuncia::class);
        return Inertia::render('Asistencias/Form');
    }
}
