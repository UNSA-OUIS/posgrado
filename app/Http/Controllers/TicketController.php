<?php

namespace App\Http\Controllers;

use App\Exports\UniformesExport;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Uniforme;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function buscarTicket(Request $request)
    {
        if ($request->opcion_busqueda == 'DNI') {
            $result = Ticket::where('dni', $request->filtro)->firstOrFail();
        }
        else if ($request->opcion_busqueda == 'APN'){
            $result = Ticket::where('apn', 'like', '%' . $request->filtro . '%')->get();
        }

        return $result;        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->has('page')){
            return Inertia::render('Tickets/Listar');
        }

        $query = Uniforme::with('usuario')->whereRaw("dni like ?", ['%' . $request->filter . '%'])
                    ->orwhereRaw("LOWER(apn) like ?", ['%' . $request->filter . '%']);

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {                        
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }
        else {
            $query = $query;
        }        

        return $query->paginate($request->size); 
    }

    public function listar(Request $request)
    {
        $recogidos = Uniforme::where('recogido', true)->count();
        $pendientes = Uniforme::where('recogido', false)->count();
        return Inertia::render('Tickets/Listar', compact('recogidos', 'pendientes'));
    }

    public function listarMisTickets(Request $request)
    {
        $usuario = Auth::user();
        $recogidos = Uniforme::where('recogido', true)->where('user_id', $usuario->id)->count();
        return Inertia::render('Tickets/ListarMisTickets', compact('recogidos'));
    }

    public function exportTickets(Request $request)
    {
        return Excel::download(new UniformesExport(), 'tickets2022.xlsx');
    }

    public function filtrarMisTickets(Request $request)
    {
        if (!$request->has('page')){
            return Inertia::render('Tickets/ListarMisTickets');
        }

        $usuario = Auth::user();

        $query = Uniforme::where('user_id', $usuario->id)
                ->where(function ($q) use ($request) {        
                    $q->whereRaw("dni like ?", ['%' . $request->filter . '%'])
                    ->orwhereRaw("LOWER(apn) like ?", ['%' . $request->filter . '%']);
                });

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {                        
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }
        else {
            $query = $query;
        }        

        return $query->paginate($request->size); 
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {     
        try {
            $ticket->recogido = true;
            $ticket->update();
            $result = ['successMessage' => 'Ticket registrado con éxito', 'error' => false];
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar el ticket', 'error' => true];
            \Log::error('TicketController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
