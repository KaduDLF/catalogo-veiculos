<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contato_mensagens', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->string('email', 254);
            $table->string('telefone', 25);
            $table->string('assunto');
            $table->text('mensagem');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contato_mensagens');
    }
};
