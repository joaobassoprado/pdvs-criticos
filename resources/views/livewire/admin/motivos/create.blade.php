<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Illuminate\Support\Facades\Gate;
use Mary\Traits\Toast;

use App\Models\Motivo;
use App\Models\Empresa;


use function Livewire\Volt\{state, layout, mount, uses, rules};

uses([Toast::class]);

state(['name', 'empresa_id']);
state(['empresas' => []]);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.create')) {
        return redirect(route('errors.403'));
    }

    $this->empresas = Empresa::get()->map(fn($e) => ['id' => $e->id, 'name' => $e->id . ' - ' . $e->name]);

});

rules([
    'name' => ['required'],
    'empresa_id' => ['required'],
])->messages([
    'empresa_id.required' => 'Insira o ID da empresa.',
    'name.required' => 'Insira o nome do motivo.',
]);

$save = function () {
    $data = $this->validate();

    try {
        Motivo::create([
            'name' => $data['name'],
            'empresa_id' => $data['empresa_id'],
            'created_by' => Ad::username(),
            'created_at' => Carbon::now(),
            'updated_by' => Ad::username(),
            'updated_at' => Carbon::now(),
        ]);

        $this->success('Motivo criado com sucesso!');

        return redirect(route('admin.motivos.index'));
    } catch (Exception $e) {
        return $this->error('Não foi possível adicionar motivo.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Cadastro de Motivo</h1>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="flex flex-col gap-2 bg-white mt-2 p-4 shadow rounded">
                <x-input label="Descrição do Motivo:" wire:model="name" icon="o-user" placeholder="descrição do motivo"/>

                  <x-select label="Empresa:" wire:model="empresa_id" icon="o-cog-6-tooth" :options="$this->empresas"
                          placeholder="Selecione uma empresa..." placeholder-value="0"/>
            </div>

            <x-button class="btn-sm btn-success mt-2 w-full" label="SALVAR" icon="o-check" type="submit"/>
        </form>
    </div>
</div>
