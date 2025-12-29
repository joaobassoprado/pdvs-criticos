<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Mary\Traits\Toast;

use App\Models\Empresa;
use App\Models\Motivo;

use function Livewire\Volt\{state, layout, mount, uses, with, usesPagination};

usesPagination();

uses([Toast::class]);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.view-any')) {
        return redirect(route('errors.403'));
    }
});

with(function () {
    $motivos = Motivo::query()->withTrashed()->orderBy('name', 'asc');

    return [
        'motivos' => $motivos->paginate(15),
    ];
});


$inativarMotivo = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.delete')) {
        return $this->error('Sem permissão para inativar o motivo.');
    }

    try {
        Motivo::find($id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => Ad::username(),
        ]);

        return $this->success('Motivo inativado com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel inativar o motivo.');
    }
};

$reativarMotivo = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.restore')) {
        return $this->error('Sem permissão para reativar o motivo.');
    }

    try {
        Motivo::withTrashed()->find($id)->restore();
        return $this->success('Motivo reativado com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel reativar o motivo.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Motivos Cadastrados</h1>
            <x-button class="btn-sm btn-success" label="ADICIONAR MOTIVO" icon="o-plus"
                link="{{ route('admin.motivos.create') }}" />
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-2">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Nome</th>
                    <th class="py-2 px-4 border-b">Empresa</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse ($motivos as $motivo)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-4 border-b text-center">{{ $motivo->id }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $motivo->name }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $motivo->empresa->name }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ !$motivo->deleted_at ? 'Ativo' : 'Inativo' }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <x-button class="btn-info btn-sm" tooltip="Editar Motivo." icon="o-pencil"
                                link="{{ route('admin.motivos.update', ['id' => $motivo->id]) }}" />

                            @if (!$motivo->deleted_at)
                                <x-button tooltip="Inativar motivo." icon="o-trash" class="btn-error btn-sm text-white"
                                    wire:confirm="Deseja realmente inativar esse motivo?"
                                    wire:click="inativarMotivo({{ $motivo->id }})" />
                            @else
                                <x-button class="btn-sm btn-success" icon="o-check" tooltip="Reativar empresa."
                                    wire:confirm="Deseja realmente reativar essa empresa?"
                                    wire:click="reativarMotivo({{ $motivo->id }})" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-4 border-b text-center ">Não há motivos cadastrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $motivos->links() }}
    </div>
</div>
