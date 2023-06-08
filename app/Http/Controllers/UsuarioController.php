<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Rol;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UsuarioStoreRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Models\Programa;
use App\Models\UserPrograma;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function listar()
    {
        $this->authorize("viewAny", User::class);
        return Inertia::render('Usuarios/Listar');
    }

    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $query = User::where('name', 'like', '%' . $request->filter . '%')
            ->orWhere('email', 'like', '%' . $request->filter . '%')
            ->orWhereHas('roles', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->filter . '%');
            });
        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        } else {
            $query = $query->withTrashed();
        }

        return $query->paginate($request->size);
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $usuario = new User();
        $usuario->name = "";
        $usuario->email = "";
        $usuario->password = "12345678";
        $usuario->roles_seleccionados = array();
        $roles = Rol::select('name as value', 'name as text')->orderBy('name', 'asc')->get();

        return Inertia::render('Usuarios/NuevoMostrar', compact('usuario', 'roles'));
    }

    public function store(UsuarioStoreRequest $request)
    {
        $this->authorize('create', User::class);

        try {
            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->tipo_usuario = "ADMINISTRADOR";
            $usuario->password = bcrypt("password");
            $usuario->syncRoles($request->roles_seleccionados);
            $usuario->save();

            $result = ['successMessage' => 'Usuario registrado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el usuario'];
            Log::error('UserController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('usuarios.iniciar')->with($result);
    }

    public function show(User $usuario)
    {
        $this->authorize('view', $usuario);

        $roles = Rol::select('name as value', 'name as text')->orderBy('name', 'asc')->get();
        $usuario->roles_seleccionados = $usuario->getRoleNames();

        $user_programas = UserPrograma::with('programa')
            ->where('user_id', $usuario->id)
            ->orderBy('nues')
            ->get();

        foreach ($user_programas as $up) {
            $up->especialidad;
        }

        $programas = Programa::where('nive', 'X')
            ->select('area', 'facu', 'nues', 'escu', 'espe', 'nesc')
            ->orderBy('nesc')
            ->get();

        return Inertia::render('Usuarios/NuevoMostrar', compact('usuario', 'roles', 'user_programas', 'programas'));
    }

    public function edit($id)
    {
        //
    }

    public function update(UsuarioUpdateRequest $request, User $usuario)
    {
        $this->authorize('update', $usuario);

        try {
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->syncRoles($request->roles_seleccionados);
            $roles = $usuario->getRoleNames()->toArray();
            if (in_array("Administrador", $roles) || in_array("Superadmin", $roles)) {
                $usuario->tipo_usuario = 'ADMINISTRADOR';
            } else {
                $usuario->tipo_usuario = 'ADMINISTRADO';
            }
            if ($request->password != "") {
                $usuario->password = bcrypt($request->password);
            }
            $usuario->update();
            $result = ['successMessage' => 'Usuario actualizado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el usuario'];
            Log::error('UsuarioController@update, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('usuarios.iniciar')->with($result);
    }

    public function destroy(User $usuario)
    {
        $this->authorize('delete', $usuario);

        try {
            $usuario->delete();
            $result = ['successMessage' => 'usuario eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el usuario'];
            Log::error('UsuarioController@destroy, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function restore($usuario_id)
    {
        $usuario = User::withTrashed()->findOrFail($usuario_id);
        $this->authorize('restore', $usuario);

        try {
            $usuario->restore();
            $result = ['successMessage' => 'Usuario restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el usuario'];
            Log::error('UsuarioController@restore, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function destroyProgramaAutorizado(UserPrograma $user_programa)
    {
        try {
            $user_programa->delete();
            $result = ['successMessage' => 'programa removido con éxito del usuario'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo remove el programa del usuario'];
            Log::error('UsuarioController@destroyProgramaAutorizado, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function storeProgramaAutorizado(Request $request)
    {
        try {
            $user_programa = UserPrograma::create([
                'user_id' => auth()->user()->id,
                'nues' => $request->nues,
                'espe' => $request->espe
            ]);

            $result = ['successMessage' => 'Programa autorizado a el usuario con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo autorizar el programa a el usuario'];
            Log::error('UserController@storeProgramaAutorizado, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }
}
