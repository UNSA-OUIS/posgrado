<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\UserPrograma;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function getPosgradoStudents(Request $request)
    {
        $request->validate([
            'anho' => 'numeric|digits:4|required',
            'nues' => 'string|required',
            'espe' => 'string|nullable',
        ]);

        UserPrograma::where('user_id', auth()->user()->id)
            ->where('nues', $request->nues)
            ->where('espe', $request->espe)
            ->firstOrFail();

        $year = substr($request->anho, -2);
        $matriculados = Matricula::with('estudiante:cui,apn')
            ->where('nues', $request->nues)
            ->where('espe', $request->espe)
            ->where('cod0', 'regexp', "^.{2}{$year}.{4}$")
            ->orderBy('cui')
            ->get();

        return $matriculados;
    }
}
