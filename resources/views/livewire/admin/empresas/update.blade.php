<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Mary\Traits\Toast;

use App\Models\Empresa;

use function Livewire\Volt\{state, layout, mount, uses, rules};

uses([Toast::class]);

state(['id'])->url();
state(['empresa', 'name', 'cnpj']);
state(['empresas' => []]);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.view')) {
        return redirect(route('errors.403'));
    }

    $this->empresa = Empresa::withTrashed()->find($this->id);
    if (!$this->empresa) {
        return redirect(route('admin.empresas.index'));
    }

    $this->name = $this->empresa->name ?? '';
    $this->cnpj = $this->empresa->cnpj ?? '';
});

rules([
    'name' => ['required' ],
    'cnpj' => ['required' ],
])->messages([
    'name.required' => 'Insira o nome da empresa.',
    'cnpj.required' => 'Insira o CNPJ da empresa.',
]);

$update = function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.update')) {
        return $this->error('Sem permissão para editar.');
    }

    $data = $this->validate();

    try {
        $this->empresa->update([
            'name' => $data['name'],
            'cnpj' => $data['cnpj'],
            'updated_by' => Ad::username(),
            'updated_at' => Carbon::now(),
        ]);

        $this->success('Empresa editada com sucesso!');

        return redirect(route('admin.empresas.index'));
    } catch (Exception $e) {
        dd($e)->getMessage(); 
        return $this->error('Não foi possível editar empresa.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Editar Empresa - {{ $this->empresa->name }}</h1>
        </div>
        <form wire:submit.prevent="update">
            @csrf
            <div class="flex flex-col gap-2 bg-white mt-2 p-4 shadow rounded">
                <x-input label="Nome da Empresa:" wire:model="name" icon="o-user" placeholder="nome da empresa" />

                <x-input label="CNPJ:" wire:model="cnpj" icon="o-user" placeholder="00.000.000/0000-00" />
            </div>

            <x-button class="btn-sm btn-success mt-2 w-full" label="SALVAR" icon="o-check" type="submit" />
        </form>
    </div>
</div>
