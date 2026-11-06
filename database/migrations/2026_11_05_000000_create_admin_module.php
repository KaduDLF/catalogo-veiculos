<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
        Schema::table('veiculos', function (Blueprint $table) {
            $table->date('data_venda')->nullable()->index();
            $table->json('itens_confirmados')->nullable();
        });
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->cascadeOnDelete();
            $table->char('sessao', 64);
            $table->timestamp('created_at')->index();
            $table->unique(['veiculo_id', 'sessao']);
        });
        Schema::create('preferencias', function (Blueprint $table) {
            $table->string('campo')->primary();
            $table->text('valor');
        });
        Schema::table('contato_mensagens', fn (Blueprint $table) => $table->timestamp('lida_em')->nullable());
    }

    public function down(): void
    {
        Schema::table('contato_mensagens', fn (Blueprint $table) => $table->dropColumn('lida_em'));
        Schema::dropIfExists('preferencias');
        Schema::dropIfExists('visitas');
        Schema::table('veiculos', function (Blueprint $table) {
            $table->dropIndex(['data_venda']);
            $table->dropColumn(['data_venda', 'itens_confirmados']);
        });
        Schema::dropIfExists('users');
    }
};
