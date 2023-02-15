<?php

namespace App\Http\Controllers;

use App\Http\Requests\DenunciaStoreRequest;
use App\Http\Requests\StoreDenunciaRequest;
use App\Jobs\EnviarCorreosJob;
use App\Models\Denuncia;
use App\Models\User;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DenunciaController extends Controller
{
    
    public function mis_denuncias()
    {
        $this->authorize("viewAny", Denuncia::class);
        return Inertia::render('Denuncias/Listar');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", Denuncia::class);
        $query = Denuncia::where('administrado_id', 'like', Auth::user()->id);

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
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
        $this->authorize('create', Denuncia::class);
        $form = Auth::user();
        return Inertia::render('Denuncias/Formulario', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DenunciaStoreRequest $request)
    {
        $this->authorize('create', Denuncia::class);

        //return $request->url_archivo;
        DB::beginTransaction();
        try {
            $user = User::where('id', Auth::user()->id)->first();
            $user->tipo_documento = $request->tipo_documento;
            $user->nro_documento = $request->nro_documento;
            $user->nombres = $request->nombres;
            $user->apellidos = $request->apellidos;
            $user->telefono = $request->telefono;
            $user->email_personal = $request->email_personal;
            $user->domicilio = $request->domicilio;
            $user->update();

            $denuncia = new Denuncia();
            $ultimo = Denuncia::latest('created_at')->first();

            if (!$ultimo) {
                $denuncia->codigo = 'DU-0001';
            } else {
                $ultimo->codigo = substr($ultimo['codigo'], 3) + 1;
                $denuncia->codigo = 'DU-' . str_pad($ultimo['codigo'], 4, "0", STR_PAD_LEFT);
            }
            $denuncia->administrado_id = Auth::user()->id;
            $denuncia->descripcion = $request->descripcion;
            $archivo =  $request->file('url_archivo');
            if ($archivo) {
                $nombre_archivo = $archivo->getClientOriginalName();
                $request->file('url_archivo')->move(storage_path('app/public'), $nombre_archivo);
                $denuncia->url_archivo = $nombre_archivo;
            } else {
                $denuncia->url_archivo = null;
            }
            //$extension_archivo = $archivo->getClientOriginalExtension();
            //if ($extension_archivo === 'pdf' || $extension_archivo === 'jpg' || $extension_archivo === 'png') {

            // } else {
            // $result = ['errorMessage' => 'No se admite el formato de archivo, solo se aceptan archivos con formato jpg, png y pdf'];
            // DB::rollback();
            // return redirect()->route('mis-denuncias.listar')->with($result);
            //}

            $denuncia->save();

            DB::commit();

            //Envío de correos
            $data = [
                'user' => $user,
                'denuncia' => $denuncia,
                'tipo' => 'registrado'
            ];
            EnviarCorreosJob::dispatch($data);

            $result = ['successMessage' => 'Denuncia registrada de manera exitosa'];
            return redirect()->route('mis-denuncias.listar')->with($result);
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar la denuncia, intente nuevamente'];
            Log::error('DenunciaController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
            return redirect()->route('mis-denuncias.listar')->with($result);
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar la denuncia, intente nuevamente'];
            Log::error('DenunciaController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
            return redirect()->route('mis-denuncias.listar')->with($result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Denuncia $denuncia)
    {
        $this->authorize('view', $denuncia);
        $administrado = $denuncia->administrado()->first();

        return Inertia::render('Denuncias/FormularioRpta', compact('administrado', 'denuncia'));
    }

    public function consultar(Denuncia $denuncia)
    {
        $this->authorize('view', $denuncia);
        $administrado = $denuncia->administrado()->first();

        return Inertia::render('Denuncias/FormularioConsulta', compact('administrado', 'denuncia'));
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
    public function update(Request $request, Denuncia $denuncia)
    {
        $this->authorize('update', $denuncia);
        try {
            $denuncia->respuesta = $request->respuesta;
            $denuncia->estado = 'ATENDIDO';
            $denuncia->update();
            $result = ['successMessage' => 'Denuncia respondida con éxito'];
            //Envío de correos
            $data = [
                'user' => User::find($denuncia->administrado_id),
                'denuncia' => $denuncia,
                'tipo' => 'respondida'
            ];
            EnviarCorreosJob::dispatch($data);
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo responder a la denuncia', 'error' => true];
            Log::error('DenunciaController@update, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('recibidas')->with($result);
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

    public function recibidas(Request $request)
    {
        $this->authorize("recibidas", Denuncia::class);

        $filtro = $request->filter;

        $query = Denuncia::select('id', 'codigo', 'administrado_id', 'estado', 'created_at')
            ->with('administrado')
            ->where('estado', 'RECIBIDO')
            ->where(function ($query) use ($filtro) {
                $query->orWhere('codigo', 'like', '%' . $filtro . '%')
                    ->orWhere('created_at', 'like', '%' . $filtro . '%')
                    ->orWhereHas('administrado', function ($query) use ($filtro) {
                        $query->where('email', 'like', '%' . $filtro . '%')
                            ->orWhere('nombres', 'like', '%' . $filtro . '%')
                            ->orWhere('apellidos', 'like', '%' . $filtro . '%');
                    });
            });

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "false" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    public function atendidas(Request $request)
    {
        $this->authorize("atendidas", Denuncia::class);

        $filtro = $request->filter;

        $query = Denuncia::select('id', 'codigo', 'administrado_id', 'created_at', 'updated_at')
            ->with('administrado')
            ->where('estado', 'ATENDIDO')
            ->where(function ($query) use ($filtro) {
                $query->orWhere('codigo', 'like', '%' . $filtro . '%')
                    ->orWhere('created_at', 'like', '%' . $filtro . '%')
                    ->orWhereHas('administrado', function ($query) use ($filtro) {
                        $query->where('email', 'like', '%' . $filtro . '%')
                            ->orWhere('nombres', 'like', '%' . $filtro . '%')
                            ->orWhere('apellidos', 'like', '%' . $filtro . '%');
                    });
            });

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "false" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    public function ver_recibidas()
    {
        $this->authorize("recibidas", Denuncia::class);
        return Inertia::render('Denuncias/Recibidas');
    }

    public function ver_atendidas()
    {
        $this->authorize("atendidas", Denuncia::class);
        return Inertia::render('Denuncias/Atendidas');
    }

    public function reenviar(Request $request)
    {
        try {
            $denuncia = Denuncia::findOrFail($request->denuncia_id);
            $data = [
                'user' => User::find($denuncia->administrado_id),
                'denuncia' => $denuncia,
                'tipo' => 'respondida'
            ];
            EnviarCorreosJob::dispatch($data);
            $result = ['successMessage' => 'Correo reenviado con éxito', 'error' => false];
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo reenviar la respuesta', 'error' => true];
            Log::error('DenunciaController@reenviar, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }

    public function descargar_archivo(Request $request)
    {
        //return $request->all();
        if (Storage::disk('public')->exists("$request->url_archivo")) {
            $path = Storage::disk('public')->path("$request->url_archivo");
            $content = file_get_contents($path);
            return response($content)->withHeaders([
                'Content-Type' => mime_content_type($path)
            ]);
        } else {
            return redirect('/404');
        }
    }

    public function getTotales()
    {
        $total_recibidas = Denuncia::where('estado', 'RECIBIDO')->get()->count();
        $total_atendidas = Denuncia::where('estado', 'ATENDIDO')->get()->count();
        return [
            'total_recibidas' => $total_recibidas,
            'total_atendidas' => $total_atendidas,
        ];
    }
}
