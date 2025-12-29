<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Mary\Traits\Toast;

use App\Models\Reclamacao;
use App\Models\Motivo;
use App\Models\Empresa;

use function Livewire\Volt\{layout, mount, uses, with, usesPagination};

usesPagination();

uses([Toast::class]);

with(function () {
    $reclamacaos = Reclamacao::query()->withTrashed()->orderBy('id', 'asc');

    return [
        'reclamacaos' => $reclamacaos->paginate(15),
    ];
});

$inactiveReclamacao = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.delete')) {
        return $this->error('Sem permissão para inativar reclamação.');
    }

    try {
        Reclamacao::find($id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => Ad::username(),
        ]);

        return $this->success('Reclamação inativada com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel inativar reclamação.');
    }
};

$restoreReclamacao = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.restore')) {
        return $this->error('Sem permissão para reativar reclamação.');
    }

    try {
        Reclamacao::withTrashed()->find($id)->restore();
        return $this->success('Reclamação reativada com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel reativar reclamação.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Reclamações Cadastradas</h1>
            <x-button class="btn-sm btn-success" label="ADICIONAR RECLAMAÇÃO" icon="o-plus"
                      link="{{ route('reclamacoes.create') }}"/>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-2">
            <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="py-2 px-4 border-b">Motivo</th>
                <th class="py-2 px-4 border-b">Empresa</th>
                <th class="py-2 px-4 border-b">Titulo</th>
                <th class="py-2 px-4 border-b">Descrição</th>   
                <th class="py-2 px-4 border-b">Prioridade</th>   

                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Ações</th>
            </tr>
            </thead>
            <tbody class="text-gray-800">
            @forelse ($reclamacaos as $reclamacao)
                <tr class="hover:bg-slate-50">
                    <td class="py-2 px-4 border-b text-center">{{ $reclamacao->motivo->name }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $reclamacao->empresa->name }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $reclamacao->title }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $reclamacao->description }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $reclamacao->priority }}</td>

                    <td class="py-2 px-4 border-b text-center">{{ !$reclamacao->deleted_at ? 'Ativo' : 'Inativo' }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <x-button class="btn-info btn-sm" tooltip="Editar Reclamação." icon="o-pencil"
                                  link="{{ route('reclamacoes.update', ['id' => $reclamacao->id]) }}"/>

                        @if (!$reclamacao->deleted_at)
                            <x-button tooltip="Inativar Reclamação." icon="o-trash" class="btn-error btn-sm text-white"
                                      wire:confirm="Deseja realmente inativar essa reclamação?"
                                      wire:click="inactiveReclamacao({{ $reclamacao->id }})"/>
                        @else
                            <x-button class="btn-sm btn-success" icon="o-check" tooltip="Reativar Reclamação."
                                      wire:confirm="Deseja realmente reativar essa reclamação?"
                                      wire:click="restoreReclamacao({{ $reclamacao->id }})"/>
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="hover:bg-slate-50">
                    <td class="py-2 px-4 border-b text-center ">Não há reclamações cadastradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $reclamacaos->links() }}
    </div>
</div>
