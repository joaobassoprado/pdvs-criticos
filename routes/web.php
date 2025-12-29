<?php

use Livewire\Livewire;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

if (env('APP_ENV') === 'production') {
    Livewire::setScriptRoute(function ($handle) {
        return Route::get('gerenciamento_powerapps/livewire/livewire.js', $handle);
    });

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('gerenciamento_powerapps/livewire/update', $handle);
    });
}

Volt::route('/', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('/admin')->group(function () {
    Volt::route('/exportar', 'admin.exports.index')->middleware(['auth', 'verified'])->name('admin.exports.index');

    Route::prefix('/usuarios')->group(function () {
        Volt::route('/', 'admin.users.index')->middleware(['auth', 'verified'])->name('admin.users.index');
        Volt::route('/novo', 'admin.users.create')->middleware(['auth', 'verified'])->name('admin.users.create');
        Volt::route('/editar/{id}', 'admin.users.update')->middleware(['auth', 'verified'])->name('admin.users.update');
    });

    Route::prefix('/empresas')->group(function () {
        Volt::route('/', 'admin.empresas.index')->middleware(['auth', 'verified'])->name('admin.empresas.index');
        Volt::route('/novo', 'admin.empresas.create')->middleware(['auth', 'verified'])->name('admin.empresas.create');
        Volt::route('/editar/{id}', 'admin.empresas.update')->middleware(['auth', 'verified'])->name('admin.empresas.update');
    });

     Route::prefix('/motivos')->group(function () {
        Volt::route('/', 'admin.motivos.index')->middleware(['auth', 'verified'])->name('admin.motivos.index');
        Volt::route('/novo', 'admin.motivos.create')->middleware(['auth', 'verified'])->name('admin.motivos.create');
        Volt::route('/editar/{id}', 'admin.motivos.update')->middleware(['auth', 'verified'])->name('admin.motivos.update');
    });

    Route::prefix('/perfis')->group(function () {
        Volt::route('/', 'admin.profile.index')->middleware(['auth', 'verified'])->name('admin.profile.index');
        // Volt::route('/novo', 'admin.users.create')->middleware(['auth', 'verified'])->name('admin.users.create');
        Volt::route('/editar/{id}', 'admin.profile.update')->middleware(['auth', 'verified'])->name('admin.profile.update');
        Volt::route('/editar-permissoes/{id}', 'admin.profile.update-permissions')->middleware(['auth', 'verified'])->name('admin.profile.update-permissions');
    });
});

Route::prefix('/reclamacoes')->group(function () {
    Volt::route('/', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
        Volt::route('/novo', 'reclamacoes.create')->middleware(['auth', 'verified'])->name('reclamacoes.create');
        Volt::route('/editar/{id}', 'reclamacoes.update')->middleware(['auth', 'verified'])->name('reclamacoes.update');
});

require __DIR__ . '/auth.php';
