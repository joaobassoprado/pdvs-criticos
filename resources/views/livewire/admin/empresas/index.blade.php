<?php

use Carbon\Carbon;
use App\Classes\Ad;
use Mary\Traits\Toast;

use App\Models\Empresa;

use function Livewire\Volt\{state, layout, mount, uses, with, usesPagination};

usesPagination();

uses([Toast::class]);

mount(function () {
    if (!Gate::forUser(Auth::user())->allows('admin.users.view-any')) {
        return redirect(route('errors.403'));
    }
});

with(function () {
    $empresas = Empresa::query()->withTrashed()->orderBy('name', 'asc');

    return [
        'empresas' => $empresas->paginate(15),
    ];
});


$inativarEmpresa = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.delete')) {
        return $this->error('Sem permissão para inativar empresa.');
    }

    try {
        Empresa::find($id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => Ad::username(),
        ]);

        return $this->success('Empresa inativada com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel inativar empresa.');
    }
};

$reativarEmpresa = function ($id) {
    if (!Gate::forUser(Auth::user())->allows('admin.users.restore')) {
        return $this->error('Sem permissão para reativar empresa.');
    }

    try {
        Empresa::withTrashed()->find($id)->restore();
        return $this->success('Empresa reativada com sucesso');
    } catch (Exception $e) {
        return $this->error('Não foi possivel reativar empresa.');
    }
};

layout('layouts.app');

?>

<div>
    <div>
        <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
            <h1 class="font-bold text-gray-700">Empresas Cadastradas</h1>
            <x-button class="btn-sm btn-success" label="ADICIONAR EMPRESA" icon="o-plus"
                link="{{ route('admin.empresas.create') }}" />
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-2">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Nome</th>
                    <th class="py-2 px-4 border-b">CNPJ</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse ($empresas as $empresa)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-4 border-b text-center">{{ $empresa->id }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $empresa->name }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $empresa->cnpj }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ !$empresa->deleted_at ? 'Ativo' : 'Inativo' }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <x-button class="btn-info btn-sm" tooltip="Editar Empresa." icon="o-pencil"
                                link="{{ route('admin.empresas.update', ['id' => $empresa->id]) }}" />

                            @if (!$empresa->deleted_at)
                                <x-button tooltip="Inativar empresa." icon="o-trash" class="btn-error btn-sm text-white"
                                    wire:confirm="Deseja realmente inativar essa empresa?"
                                    wire:click="inativarEmpresa({{ $empresa->id }})" />
                            @else
                                <x-button class="btn-sm btn-success" icon="o-check" tooltip="Reativar empresa."
                                    wire:confirm="Deseja realmente reativar essa empresa?"
                                    wire:click="reativarEmpresa({{ $empresa->id }})" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-4 border-b text-center ">Não há empresas cadastradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $empresas->links() }}
    </div>
</div>
