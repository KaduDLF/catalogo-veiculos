<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            foreach (['marca', 'modelo', 'versao', 'cor', 'combustivel'] as $campo) {
                $table->string($campo)->nullable();
            }
            $table->text('descricao')->nullable();
            $table->json('opcionais')->nullable();
            $table->json('fotos')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('veiculos', fn (Blueprint $table) => $table->dropColumn([
            'marca', 'modelo', 'versao', 'cor', 'combustivel', 'descricao', 'opcionais', 'fotos',
        ]));
    }
};
