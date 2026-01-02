<?php

use App\Models\Pdv;
use App\Models\Pergunta;
use App\Models\Resposta;
use Carbon\Carbon;
use function Livewire\Volt\{state, mount, layout};

state([
    'pdv_id' => null,
    'cidade' => '',
    'responsavel' => '',
    'pdvs' => [],
    'perguntas' => [],
    'respostasFixas' => [
        ['id' => '1', 'name' => '1'],
        ['id' => '2', 'name' => '2'],
        ['id' => '3', 'name' => '3'],
    ],
    'respostasSelecionadas' => [],
    'observacoes' => [],
    'totalPontos' => 0,
    'mensagemSucesso' => false,
    'mensagemErro' => null,
]);

mount(function () {
    $this->pdvs = Pdv::orderBy('nome_fantasia')
        ->get()
        ->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->nome_fantasia,
        ])
        ->toArray();

    $this->perguntas = Pergunta::all();
});

$updatedRespostasSelecionadas = function () {
    $this->totalPontos = array_sum($this->respostasSelecionadas);
};

$salvar = function () {

    $this->mensagemErro = null;

    if (!$this->pdv_id || !$this->cidade || !$this->responsavel) {
        $this->mensagemErro = 'Selecione o PDV, informe a cidade e o responsável.';
        return;
    }

    if (count($this->respostasSelecionadas) < count($this->perguntas)) {
        $this->mensagemErro = 'Responda todas as perguntas antes de salvar.';
        return;
    }

    foreach ($this->respostasSelecionadas as $perguntaId => $resposta) {
        Resposta::create([
            'data' => Carbon::now(),
            'responsavel' => $this->responsavel,
            'pdv_id' => $this->pdv_id,
            'cidade' => $this->cidade,
            'pergunta_id' => $perguntaId,
            'resposta' => $resposta,
            'observacao' => $this->observacoes[$perguntaId] ?? null,
            'total_pontos' => $this->totalPontos,
        ]);
    }

    $this->mensagemSucesso = true;
};

layout('layouts.app');
?>

<div class="max-w-3xl mx-auto space-y-6">

    {{-- LOGO VIRGINIA --}}
    <div class="flex justify-center">
        <img
            src="{{ asset('assets/images/logo-virginia.png') }}"
            alt="Logo Virginia"
            class="h-24 object-contain"
        >
    </div>

    <h1 class="text-lg font-bold text-center">
        Formulário de Avaliação
    </h1>

    {{-- PONTUAÇÃO COM COR --}}
    @php
        $cor = 'bg-green-100 text-green-800 border-green-300';

        if ($totalPontos > 10 && $totalPontos < 30) {
            $cor = 'bg-yellow-100 text-yellow-800 border-yellow-300';
        }

        if ($totalPontos >= 30) {
            $cor = 'bg-red-100 text-red-800 border-red-300';
        }
    @endphp

    <div class="rounded-lg border p-4 text-center font-bold {{ $cor }}">
        Pontuação atual: {{ $totalPontos }} pontos
    </div>

    {{-- SUCESSO --}}
    @if ($mensagemSucesso)

        <div class="rounded-lg border border-green-300 bg-green-100 p-6 text-green-800 text-center text-lg font-semibold">
            ✅ Formulário enviado com sucesso!
        </div>

    @else

        {{-- ERRO --}}
        @if ($mensagemErro)
            <div class="rounded-lg border border-red-300 bg-red-100 p-3 text-red-800 text-center">
                ❌ {{ $mensagemErro }}
            </div>
        @endif

        {{-- PDV (COM PESQUISA / LUPA) --}}
        <x-select
            label="PDV"
            placeholder="Selecione o PDV"
            wire:model.live="pdv_id"
            :options="$pdvs"
            searchable
        />

        {{-- CIDADE --}}
        <x-input
            label="Cidade"
            placeholder="Digite a cidade"
            wire:model.live="cidade"
        />

        {{-- RESPONSÁVEL --}}
        <x-input
            label="Responsável"
            placeholder="Nome do responsável"
            wire:model.live="responsavel"
        />

        {{-- PERGUNTAS --}}
        @if ($pdv_id && $cidade && $responsavel)

            <hr>

            @foreach ($perguntas as $pergunta)
                <div class="space-y-2 border-b pb-4">

                    <strong>{{ $pergunta->descricao }}</strong>

                    <x-select
                        placeholder="Selecione a resposta"
                        wire:model.live="respostasSelecionadas.{{ $pergunta->id }}"
                        :options="$respostasFixas"
                        option-label="name"
                        option-value="id"
                    />

                    <textarea
                        class="w-full rounded-md border-gray-300 text-sm"
                        rows="2"
                        placeholder="Observação (opcional)"
                        wire:model.defer="observacoes.{{ $pergunta->id }}"
                    ></textarea>

                    {{-- LEGENDA --}}
                    <div class="flex gap-4 text-sm mt-1">
                        <div class="flex items-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            <span>1 – Bom / Não / Normal</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                            <span>2 – Regular / Sim / Médio</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                            <span>3 – Crítico</span>
                        </div>
                    </div>

                </div>
            @endforeach

            <x-button type="button" primary wire:click="salvar">
                Salvar Questionário
            </x-button>

        @endif

    @endif

</div>
