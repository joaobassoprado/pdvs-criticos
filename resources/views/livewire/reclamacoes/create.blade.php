<?php

use Carbon\Carbon;
use Mary\Traits\Toast;

use App\Models\Pdv;
use App\Models\Pergunta;
use App\Models\Resposta;

use function Livewire\Volt\{state, layout, mount, rules};

uses([Toast::class]);

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/
state([
    'pdv_id' => null,
    'responsavel' => '',
    'respostas' => [],
    'pdvs' => [],
    'perguntas' => [],
    'respostasPadrao' => [],
]);

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/
mount(function () {
    // PDVs
    $this->pdvs = Pdv::orderBy('nome_fantasia')
        ->get()
        ->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->codigo . ' - ' . $p->nome_fantasia
        ]);

    // Perguntas
    $this->perguntas = Pergunta::get();

    // Opções fixas (1,2,3)
    $this->respostasPadrao = [
        ['id' => '1', 'name' => '1'],
        ['id' => '2', 'name' => '2'],
        ['id' => '3', 'name' => '3'],
    ];
});

/*
|--------------------------------------------------------------------------
| RULES
|--------------------------------------------------------------------------
*/
rules([
    'pdv_id' => ['required'],
    'responsavel' => ['required', 'string'],
    'respostas' => ['required', 'array'],
]);

/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/
$save = function () {
    $this->validate();

    try {
        foreach ($this->respostas as $perguntaId => $resposta) {
            Resposta::create([
                'pdv_id' => $this->pdv_id,
                'responsavel' => $this->responsavel,
                'pergunta_id' => $perguntaId,
                'resposta' => $resposta,
                'data' => Carbon::now()->toDateString(),
            ]);
        }

        $this->success('Questionário enviado com sucesso!');

        // Limpa o formulário
        $this->reset(['pdv_id', 'responsavel', 'respostas']);

    } catch (Exception $e) {
        $this->error('Erro ao salvar o questionário.');
    }
};

layout('layouts.app');
?>

<div>
    <div class="flex flex-row justify-between items-center bg-gray-100 p-4 shadow rounded">
        <h1 class="font-bold text-gray-700">Questionário PDV</h1>
    </div>

    <form wire:submit.prevent="save" class="mt-2">

        {{-- DADOS INICIAIS --}}
        <div class="flex flex-col gap-3 bg-white p-4 shadow rounded">

            <x-input
                label="Nome do responsável"
                wire:model.live="responsavel"
                placeholder="Informe seu nome"
            />

            <x-select
                label="PDV"
                wire:model.live="pdv_id"
                :options="$pdvs"
                placeholder="Selecione o PDV"
                placeholder-value=""
            />
        </div>

        {{-- PERGUNTAS (SÓ APARECEM SE PREENCHER OS DOIS CAMPOS) --}}
        @if($pdv_id && $responsavel)

            <div class="flex flex-col gap-3 bg-white mt-3 p-4 shadow rounded">

                @foreach($perguntas as $pergunta)
                    <x-select
                        label="{{ $pergunta->descricao }}"
                        wire:model="respostas.{{ $pergunta->id }}"
                        :options="$respostasPadrao"
                        placeholder="Selecione"
                        placeholder-value=""
                    />
                @endforeach

            </div>

            <x-button
                class="btn-success mt-3 w-full"
                label="ENVIAR QUESTIONÁRIO"
                icon="o-check"
                type="submit"
            />

        @endif

    </form>
</div>
