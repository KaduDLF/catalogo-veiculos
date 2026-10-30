<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContatoController
{
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:254'],
            'telefone' => ['required', 'string', 'max:25', 'regex:/^[+()\d\s-]{8,25}$/'],
            'assunto' => ['required', 'in:Interesse em um veículo,Troca ou avaliação,Financiamento,Outros assuntos'],
            'mensagem' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        DB::table('contato_mensagens')->insert([
            ...$dados, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return to_route('contato')->with('status', 'Sua mensagem foi recebida. Para atendimento imediato, fale conosco pelo WhatsApp.');
    }
}
