<?php

namespace Database\Seeders;

use App\Models\NumeroComprobante;
use App\Models\Persona;
use App\Models\PuntosVenta;
use App\Models\User;
use App\Models\Rol;
use App\Models\TipoComprobante;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
//use Spatie\Permission\Models\Role;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Rol::create([
            'name' => 'Superadmin',
            'guard_name' => 'sanctum'
        ]);

        $administrador = Rol::create([
            'name' => 'Administrador',
            'guard_name' => 'sanctum'
        ]);

        $administrado = Rol::create([
            'name' => 'Administrado',
            'guard_name' => 'sanctum'
        ]);

        User::create([
            'name' => 'Caja UNSA',
            'email' => 'cajaunsa@unsa.edu.pe',
            'password' => bcrypt('cajaunsa2020')
        ]);

        User::create([
            'name' => 'Aaron Arturo Tapia Rios',
            'email' => 'atapiar@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Gary Núñez Ccahuaya',
            'email' => 'gnunezc@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Renzo Siza Tejada',
            'email' => 'rsiza@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'ccabrera',
            'email' => 'ccabrera@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'epachecop',
            'email' => 'epachecop@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'aportugalc',
            'email' => 'aportugalc@unsa.edu.pe',
            'tipo_usuario' => 'ADMINISTRADOR',
            'password' => bcrypt('password')
        ]);

        // usuario Superadmin
        $caja_unsa = User::find(1);
        $caja_unsa->assignRole('Administrado');

        $aaron = User::find(15);
        $aaron->assignRole('Superadmin');
        $aaron->assignRole('Administrador');

        $gary = User::find(3);
        $gary->assignRole('Superadmin');
        $gary->assignRole('Administrador');

        $renzo = User::find(4);
        $renzo->assignRole('Superadmin');
        $renzo->assignRole('Administrador'); 
        
        $admin1 = User::find(5);
        $admin1->assignRole('Administrador');

        $admin2 = User::find(6);
        $admin2->assignRole('Administrador');

        $admin3 = User::find(7);
        $admin3->assignRole('Administrador');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'Admin Dashboard', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrador);
        Permission::create(['name' => 'Administrado Dashboard', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrado);

        Permission::create(['name' => 'Listar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Roles', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Listar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Crear Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Mostrar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Editar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Eliminar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);
        Permission::create(['name' => 'Restaurar Usuarios', 'guard_name' => 'sanctum'])->assignRole($superadmin);

        Permission::create(['name' => 'Registrar Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrado);
        Permission::create(['name' => 'Ver-Propias Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrado);
        Permission::create(['name' => 'Ver-Atendidas Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrador);
        Permission::create(['name' => 'Ver-Recibidas Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrador);
        Permission::create(['name' => 'Responder Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrador);
        Permission::create(['name' => 'Reenviar Denuncias', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrador);

        Permission::create(['name' => 'Registrar Uniformes', 'guard_name' => 'sanctum'])->assignRole($superadmin, $administrado);

    
    }
}
