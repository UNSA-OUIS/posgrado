<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;

class ProgramaController extends Controller
{
    public function getEspecialidades($nues)
    {
        $especialidades = Especialidad::where('nues', $nues)
            ->whereRaw('plan = (select max(plan) from actespe where nues = ?)', [$nues])
            ->select('nues', 'numesp', 'nomesp')
            ->get();
        return $especialidades;
    }
}
