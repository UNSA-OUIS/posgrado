<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function getPosgradoStudents(Request $request)
    {
        $request->validate([
            'anho' => 'numeric|required',
            'nues' => 'string|required',
            'espe' => 'string|nullable',
        ]);

        return "enviando estudiantes";
    }
}
