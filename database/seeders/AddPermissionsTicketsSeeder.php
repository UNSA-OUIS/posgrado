<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddPermissionsTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission::create(['name' => 'Ver-Propios Tickets', 'guard_name' => 'sanctum']);
        //Permission::create(['name' => 'Ver-Reporte Tickets', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Registrar Uniformes', 'guard_name' => 'sanctum']);

    }
}
