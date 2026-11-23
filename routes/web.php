<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('change-status/group', [GroupController::class, 'changeStatus'])->name('groups.changeStatus');
    Route::get('change-status/user', [UserController::class, 'changeStatus'])->name('users.changeStatus');
    Route::resource('groups', GroupController::class);
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.members.store');
    Route::put('/groups/{group}/members/{user}', [GroupController::class, 'updateMember'])->name('groups.members.update');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.destroy');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/grupos/pdf', [ReporteController::class, 'gruposPdf'])->name('grupos.pdf');
        Route::get('/grupos/excel', [ReporteController::class, 'gruposExcel'])->name('grupos.excel');
        Route::get('/grupos/{group}/pdf', [ReporteController::class, 'grupoPdf'])->name('grupo.pdf');
        Route::get('/grupos/{group}/miembros/excel', [ReporteController::class, 'grupoMiembrosExcel'])->name('grupo.miembros.excel');
        Route::get('/usuarios/pdf', [ReporteController::class, 'usuariosPdf'])->name('usuarios.pdf');
        Route::get('/usuarios/excel', [ReporteController::class, 'usuariosExcel'])->name('usuarios.excel');
        Route::get('/membresias/pdf', [ReporteController::class, 'membresiasPdf'])->name('membresias.pdf');
        Route::get('/membresias/excel', [ReporteController::class, 'membresiasExcel'])->name('membresias.excel');
    });
});
