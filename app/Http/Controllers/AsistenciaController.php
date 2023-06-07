<?php

namespace App\Http\Controllers;

use App\Models\ProgramaPension;
use App\Models\UserPrograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AsistenciaController extends Controller
{
    public function vista()
    {
        //$this->authorize("viewAny", Denuncia::class);
        $programas_autorizados = UserPrograma::with('programa')
            ->where('user_id', auth()->user()->id)
            ->select('nues', 'espe')
            ->distinct()
            ->get();

        $couples = array_map(function ($item) {
            $nues = $item['nues'];
            $espe = $item['espe'];

            return DB::raw("('$nues', '$espe')");
        }, $programas_autorizados->toArray());

        $programa_pensiones = ProgramaPension::whereIn(DB::raw('(nues, espe)'), $couples)->get()->groupBy('nues')->toArray();

        $programa_pensiones = array_map(function ($list) {
            //dd($list);
            $pensiones_por_espe = collect($list)->groupBy('espe')->toArray();

            $pensiones_por_anho = array_map(function ($list2) {
                return collect($list2)->groupBy('anho');
            }, $pensiones_por_espe);

            return $pensiones_por_anho;
        }, $programa_pensiones);

        $data_pensiones = [];
        foreach ($programa_pensiones as $nues => $espes) {
            $data_espes = [];
            foreach ($espes as $espe => $anhos) {
                $data_anhos = [];
                foreach ($anhos as $anho => $pensiones_monto) {
                    $data_anhos[] = [
                        'anho' => $anho,
                        'pensiones_monto' => $pensiones_monto
                    ];
                }

                $programa_aut = $programas_autorizados->where('nues', $nues)->where('espe', $espe)->first();

                $nomesp = !empty($programa_aut->especialidad) ? $programa_aut->especialidad->nomesp : '';

                $data_espes[] = [
                    'espe' => $espe,
                    'especialidad' => $nomesp,
                    'data_anhos' => $data_anhos
                ];
            }

            $nesc = !empty($programa_aut->programa) ? $programa_aut->programa->nesc : '';
            $data_pensiones[] = [
                'nues' => $nues,
                'programa' => $nesc,
                'data_espes' => $data_espes
            ];
        }

        /*$user = auth()->user();
        $programas_pensiones = ProgramaPension::join('user_programas as u', function ($join) use ($user) {
            $join->on('u.nues', '=', 'SIAC_MONTOS_POSG.nues')
                ->where('u.espe', '=', 'SIAC_MONTOS_POSG.espe')
                ->where('u.user_id', $user->id);
        })
            ->get();*/

        return Inertia::render('Asistencias/Form', compact('data_pensiones'));
    }
}
