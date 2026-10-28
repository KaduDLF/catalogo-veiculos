<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('ano', 9);
            $table->unsignedInteger('km')->default(0);
            $table->string('cambio');
            $table->decimal('preco', 12, 2);
            $table->string('foto')->nullable();
            $table->boolean('disponivel')->default(true);
            $table->boolean('em_promocao')->default(false);
            $table->decimal('preco_promocional', 12, 2)->nullable();
            $table->timestamps();
            $table->index(['disponivel', 'em_promocao', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
