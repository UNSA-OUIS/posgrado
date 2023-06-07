<?php

use App\Http\Controllers\BoletaController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;

use App\Http\Controllers\UniformeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginWithGoogleController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\EstudianteController;

use App\Http\Controllers\Pdf;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    /* $user = Auth::user();
    return Inertia::render('Dashboard', compact('user')); */
    return Inertia::render('Uniforme/Formulario');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    /* $user = Auth::user();
    return Inertia::render('Dashboard', compact('user')); */
    return Inertia::render('Uniforme/Formulario');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/administrador', function () {
    /* $user = Auth::user();
    return Inertia::render('Dashboard_Administrador', compact('user')); */
})->name('dashboard.administrador');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/administrado', function () {
    /* $user = Auth::user();
    return Inertia::render('Dashboard_Administrado', compact('user')); */
})->name('dashboard.administrado');

Route::group(["middleware" => ['auth:sanctum', 'verified']], function () {

    Route::get('/api_dni/{dni}', function ($dni) {
        return file_get_contents("https://dniruc.apisperu.com/api/v1/dni/$dni?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJkYy5jdXJzb3NAZ21haWwuY29tIn0.Oenuq4RkLDyY3ZDRc44ZyJR7NLXmBSSlwrnUbjQ4y1o");
    })->name('api_ruc.dni');

    /**************************** CRUD ROLES ***********************************/
    Route::get('/roles', [RolController::class, 'index'])->name('roles.listar');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.crear');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.registrar');
    /***************************************************************************/

    /*********************************** ROLES ********************************/
    Route::get('/roles', [RolController::class, 'listar'])->name('roles.iniciar');
    Route::get('/roles/listar', [RolController::class, 'index'])->name('roles.listar');
    Route::get('/roles/crear', [RolController::class, 'create'])->name('roles.crear');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.registrar');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.mostrar');
    Route::post('/roles/{rol}', [RolController::class, 'update'])->name('roles.actualizar');
    Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.eliminar');
    Route::post('/roles/{rol}/restaurar', [RolController::class, 'restore'])->name('roles.restaurar');
    /***************************************************************************/

    /*********************************** USUARIOS ********************************/
    Route::get('/usuarios', [UsuarioController::class, 'listar'])->name('usuarios.iniciar');
    Route::get('/usuarios/listar', [UsuarioController::class, 'index'])->name('usuarios.listar');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.crear');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.registrar');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.mostrar');
    Route::post('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.actualizar');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.eliminar');
    Route::post('/usuarios/{usuario}/restaurar', [UsuarioController::class, 'restore'])->name('usuarios.restaurar');

    Route::get('/perfil-usuario', [UsuarioController::class, 'showMyUser'])->name('usuarios.perfil');
    Route::post('/perfil-usuario/{usuario}', [UsuarioController::class, 'editMyUser'])->name('usuarios.actualizarPerfil');
    /******************************************************************************/

    /******************************* UNIFORME *****************************/
    Route::get('/tickets', function () {
        return Inertia::render('Uniforme/Formulario');
    })->name('tickets.formulario');
    Route::get('/buscarTicket', [UniformeController::class, 'buscarTicket'])->name('tickets.buscar_ticket');
    Route::post('/registrarTicket/{ticket}', [UniformeController::class, 'update'])->name('tickets.registrar');

    /*********************************************************************/

    /******************************* DENUNCIAS *********************************/
    // Route::get('/tickets', [DenunciaController::class, 'create'])->name('denuncias.formulario');
    Route::get('/mis-denuncias', [DenunciaController::class, 'mis_denuncias'])->name('mis-denuncias.listar');
    Route::post('/denuncias/registrar', [DenunciaController::class, 'store'])->name('denuncias.registrar');
    Route::post('/denuncias/listar', [DenunciaController::class, 'index'])->name('denuncias.listar');
    Route::get('/recibidas', [DenunciaController::class, 'ver_recibidas'])->name('recibidas');
    Route::get('/atendidas', [DenunciaController::class, 'ver_atendidas'])->name('atendidas');
    Route::get('/totales', [DenunciaController::class, 'getTotales'])->name('denuncias.totales');
    Route::get('/denuncias/{denuncia}', [DenunciaController::class, 'show'])->name('denuncias.mostrar');
    Route::get('/consulta_denuncias/{denuncia}', [DenunciaController::class, 'consultar'])->name('denuncias.consultar');
    Route::post('/denuncias/{denuncia}', [DenunciaController::class, 'update'])->name('denuncias.actualizar');
    Route::get('/denuncias_recibidas', [DenunciaController::class, 'recibidas'])->name('denuncias.recibidas');
    Route::get('/denuncias_atendidas', [DenunciaController::class, 'atendidas'])->name('denuncias.atendidas');
    Route::get('/reenviarRespuesta', [DenunciaController::class, 'reenviar'])->name('denuncias.reenviar');
    Route::get('/archivo', [DenunciaController::class, 'descargar_archivo'])->name('archivo.descargar');
    /***************************************************************************/

    Route::get('/tickets-atendidos', [TicketController::class, 'listarMisTickets'])->name('uniformes.listarMisTickets');
    Route::get('/tickets-atendidos/listar', [TicketController::class, 'filtrarMisTickets'])->name('tickets.filtrarMisTickets');
    Route::get('/tickets-reporte', [TicketController::class, 'listar'])->name('uniformes.iniciar');
    Route::get('/tickets-reporte/listar', [TicketController::class, 'index'])->name('tickets.listar');
    Route::get('/tickets-reporte/export', [TicketController::class, 'exportTickets'])->name('tickets.exportTickets');

    // PENSIONES POSGRADO
    Route::get('/asistencias', [AsistenciaController::class, 'vista'])->name('asistencias');
    Route::post("/students/posgrado", [EstudianteController::class, "getPosgradoStudents"])->name('students.posgrado');
});

Route::get('/google', [LoginWithGoogleController::class, 'redirectToGoogle'])->name('google');;
Route::get('/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);
