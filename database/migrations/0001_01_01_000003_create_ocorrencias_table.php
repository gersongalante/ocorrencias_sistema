<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocorrencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Agente que registou
            $table->foreignId('esquadra_id')->nullable()->constrained('esquadras')->nullOnDelete();
            $table->string('tipo');
            $table->dateTime('data_hora');
            $table->string('provincia');
            $table->string('municipio');
            $table->string('bairro');
            $table->string('rua')->nullable();
            $table->text('vitimas');
            $table->text('descricao');
            $table->string('estado')->default('Aberta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocorrencias');
    }
}; 