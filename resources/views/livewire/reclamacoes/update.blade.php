<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Illuminate\Support\Facades\Gate;
use Mary\Traits\Toast;

use App\Models\Reclamacao;
use App\Models\Empresa;
use App\Models\Motivo;



use function Livewire\Volt\{state, layout, mount, uses, rules};

uses([Toast::class]);

state(['id'])->url();
state(['reclamacao', 'motivo_id', 'empresa_id', 'title', 'description', 'priority']);
state(['empresas' => [], 'motivos' => [], 'prioridades' => []]);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('apps.view-any')) {
        return redirect(route('errors.403'));
    }

    $this->reclamacao = Reclamacao::withTrashed()->find($this->id);

    $this->motivo_id = $this->reclamacao->motivo_id ?? '';
    $this->empresa_id = $this->reclamacao->empresa_id ?? '';
    $this->title = $this->reclamacao->title ?? '';
    $this->description = $this->reclamacao->description ?? '';
    $this->priority = $this->reclamacao->priority ?? '';

    $this->prioridades = [['id' => 'Baixa', 'name' => 'Baixa'], ['id' => 'Média', 'name' => 'Média'], ['id' => 'Alta', 'name' => 'Alta'], ['id' => 'Urgente', 'name' => 'Urgente']];


    $this->empresas = Empresa::get()->map(fn($e) => ['id' => $e->id, 'name' => $e->name]);
    $this->motivos = Motivo::get()->map(fn($m) => ['id' => $m->id, 'name' => $m->name]);

});


rules([
    'motivo_id' => ['required'],
    'empresa_id' => ['required'],
    'title' => ['required'],
    'description' => ['required'],
    'priority' => ['required'],
])->messages([
    'motivo_id.required' => 'Selecione o motivo da reclamação.',
    'empresa_id.required' => 'Selecione a empresa da reclamação.',
    'title.required' => 'Insira o título da reclamação.',
    'description.required' => 'Insira a descrição da reclamação.',
    'priority.required' => 'Selecione a prioridade da reclamação.',
]);

$update = function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.update')) {
        return $this->error('Sem permissão para editar.');
    }

    $data = $this->validate();

    try {
        $this->reclamacao->update([
            'motivo_id' => $data['motivo_id'],
            'empresa_id' => $data['empresa_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'updated_by' => Ad::username(),
            'updated_at' => Carbon::now(),
        ]);

        $this->success('Reclamação criada com sucesso!');

        return redirect(route('dashboard'));
    } catch (Exception $e) {
        return $this->error('Não foi possível adicionar reclamação.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Editar Reclamação {{ $this->reclamacao->id }}</h1>
        </div>
        <form wire:submit.prevent="update">
            @csrf
            <div class="flex flex-col gap-2 bg-white mt-2 p-4 shadow rounded">
                <x-select label="Empresa:" wire:model="empresa_id" icon="o-building-office" :options="$this->empresas"
                          placeholder="Selecione uma empresa..." placeholder-value="0"/>
                <x-select label="Motivo:" wire:model="motivo_id" :options="$this->motivos" icon="o-chat-bubble-oval-left"
                          placeholder="Selecione um motivo..." placeholder-value="0"/>
                <x-select label="Prioridade:" wire:model="priority" :options="$this->prioridades" icon="o-user"
                          placeholder="Selecione uma prioridade..." placeholder-value="0"/>

                <x-input label="Título:" wire:model="title" icon="o-user" placeholder="título da reclamação"/>
                <x-textarea icon="o-user" label="Descrição:" wire:model="description" icon="o-user" placeholder="Descreva a reclamação"/>
            </div>

            <x-button class="btn-sm btn-success mt-2 w-full" label="SALVAR" icon="o-check" type="submit"/>
        </form>
    </div>
</div>
