<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('data');
            $table->string('responsavel');
            $table->foreignId('pdv_id')->nullable()->constrained('p_d_v_s')->onDelete('cascade');
            $table->foreignId('pergunta_id')->nullable()->constrained('perguntas')->onDelete('cascade');
            $table->string('resposta');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas');
    }
};
