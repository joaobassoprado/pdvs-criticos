<?php

use Livewire\Livewire;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

if (env('APP_ENV') === 'production') {
    Livewire::setScriptRoute(function ($handle) {
        return Route::get('pdv_critico/livewire/livewire.js', $handle);
    });

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('pdv_critico/livewire/update', $handle);
    });
}

/*
|--------------------------------------------------------------------------
| ROTA DO FORMULÁRIO (UMA PÁGINA)
|--------------------------------------------------------------------------
*/

Volt::route('/formulario', 'formulario')->name('formulario');
