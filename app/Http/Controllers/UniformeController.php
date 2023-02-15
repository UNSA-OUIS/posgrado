<?php

namespace App\Http\Controllers;

use App\Models\Uniforme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UniformeController extends Controller
{

    public function buscarTicket(Request $request)
    {
        if ($request->opcion_busqueda == 'DNI') {
            $result = Uniforme::where('dni', $request->filtro)->firstOrFail();
        }
        else if ($request->opcion_busqueda == 'APN'){
            $result = Uniforme::where('apn', 'like', '%' . $request->filtro . '%')->get();
        }

        return $result;        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Uniforme  $uniforme
     * @return \Illuminate\Http\Response
     */
    public function show(Uniforme $uniforme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Uniforme  $uniforme
     * @return \Illuminate\Http\Response
     */
    public function edit(Uniforme $uniforme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Uniforme  $uniforme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Uniforme $ticket)
    {
        try {
            $usuario = Auth::user();
            $ticket->recogido = true;
            $ticket->user_id = $usuario->id;
            $ticket->update();
            $result = ['successMessage' => 'Ticket registrado con éxito', 'error' => false];
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar el ticket', 'error' => true];
            Log::error('UniformeController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Uniforme  $uniforme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Uniforme $uniforme)
    {
        //
    }
}
