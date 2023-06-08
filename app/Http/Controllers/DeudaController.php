<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    public function getDeudasForProgramaPension(Request $request)
    {
        $request->validate([
            'anho' => 'required|numeric|digits:4',
            'nues' => 'required|string',
            'espe' => 'nullable|string',
            'pension' => 'numeric|min:1|max:99'
        ]);

        $docref = "P-P{$request->pension}-{$request->anho}";
        $deudas = Deuda::where('nues', $request->nues)
            ->where('espe', $request->espe)
            ->where('documentReference', $docref)
            ->select('id', 'cui', 'paymentDetail', 'status')
            ->get();

        return $deudas;
    }
}
