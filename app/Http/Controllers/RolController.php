<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RolStoreRequest;
use App\Http\Requests\RolUpdateRequest;
use App\Jobs\EnviarCorreosJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Contracts\Role;

class RolController extends Controller
{
    public function listar()
    {
        $this->authorize("viewAny", Rol::class);
        return Inertia::render('Roles/Listar');
    }
    
    public function index(Request $request)
    {
        $this->authorize("viewAny", Rol::class);

        $query = Rol::where('name', 'like', '%' . $request->filter . '%'); 

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {                        
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }
        else {
            $query = $query->withTrashed();
        }        

        return $query->paginate($request->size);   
    }

    public function create()
    {
        $this->authorize('create', Rol::class);
        $rol = new Rol();
        $rol->name = "";
        $rol->permisos_seleccionados = array();
        $permissions = Permission::all(); 

        return Inertia::render('Roles/NuevoMostrar', compact('rol', 'permissions'));
    }

    public function store(RolStoreRequest $request)
    {
        $this->authorize('create', Rol::class);
        
        try {           
            $rol = new Rol();
            $rol->name = $request->name;
            $rol->syncPermissions($request->permisos_seleccionados);
            $rol->save();
            $result = ['successMessage' => 'Rol registrado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el rol'];
            Log::error('RolController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('roles.iniciar')->with($result); 
    }

    public function show(Rol $rol)
    {        
        $this->authorize('view', $rol);
        
        $rol_permissions = $rol->permissions()->select('id')->get();     
        $permisos_seleccionados = array();

        foreach ($rol_permissions as $key => $rol_permission) {
            $permisos_seleccionados[$key] = $rol_permission->id;
        }
        
        $rol->permisos_seleccionados = $permisos_seleccionados;
        $permissions = Permission::all();   
        
        return Inertia::render('Roles/NuevoMostrar', compact('rol', 'permissions'));
    }

    public function edit($id)
    {
        //
    }

    public function update(RolUpdateRequest $request, Rol $rol)
    {
        $this->authorize('update', $rol);
        
        try {                       
            $rol->name = $request->name;
            $rol->syncPermissions($request->permisos_seleccionados); 
            $rol->update();
            $result = ['successMessage' => 'Rol actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el rol'];
            Log::error('RolController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('roles.iniciar')->with($result); 
    }

    public function destroy(Rol $rol)
    {
        $this->authorize('delete', $rol);
        
        try {                       
            $rol->delete();            
            $result = ['successMessage' => 'Rol eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el rol'];
            Log::error('RolController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);  
    }

    public function restore($rol_id) 
    {   
        $rol = Rol::withTrashed()->findOrFail($rol_id);  
        $this->authorize('restore', $rol);
        
        try {                       
            $rol->restore();
            $result = ['successMessage' => 'Rol restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el rol'];
            Log::error('RolController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);            
    }
}
