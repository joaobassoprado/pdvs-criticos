<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Illuminate\Support\Facades\Gate;
use Mary\Traits\Toast;

use App\Models\Empresa;


use function Livewire\Volt\{state, layout, mount, uses, rules};

uses([Toast::class]);

state(['name', 'cnpj']);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.create')) {
        return redirect(route('errors.403'));
    }

});

rules([
    'name' => ['required', 'unique:empresas,name'],
    'cnpj' => ['required', 'unique:empresas,cnpj'],
])->messages([
    'name.required' => 'Insira o nome da empresa.',
    'name.unique' => 'empresa já cadastrada.',
    'cnpj.required' => 'Insira o CNPJ da empresa.',
    'cnpj.unique' => 'CNPJ já cadastrado.',
]);

$save = function () {
    $data = $this->validate();

    try {
        Empresa::create([
            'name' => $data['name'],
            'cnpj' => $data['cnpj'],
            'created_by' => Ad::username(),
            'created_at' => Carbon::now(),
            'updated_by' => Ad::username(),
            'updated_at' => Carbon::now(),
        ]);

        $this->success('Empresa criada com sucesso!');

        return redirect(route('admin.empresas.index'));
    } catch (Exception $e) {
        return $this->error('Não foi possível adicionar empresa.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Cadastro de Empresa</h1>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="flex flex-col gap-2 bg-white mt-2 p-4 shadow rounded">
                <x-input label="Nome da Empresa:" wire:model="name" icon="o-user" placeholder="nome da empresa"/>

                <x-input label="CNPJ:" wire:model="cnpj" icon="o-user" placeholder="00.000.000/0000-00"/>
            </div>

            <x-button class="btn-sm btn-success mt-2 w-full" label="SALVAR" icon="o-check" type="submit"/>
        </form>
    </div>
</div>
