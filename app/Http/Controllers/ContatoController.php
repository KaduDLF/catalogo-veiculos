<?php

namespace App\Http\Controllers;

use App\Mail\NovoContato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

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

        if ($destinatario = config('contato.notification_email')) {
            try {
                Mail::to($destinatario)->send(new NovoContato($dados));
            } catch (Throwable $e) {
                Log::error('Não foi possível enviar a notificação de contato.', ['exception' => $e]);
            }
        }

        return to_route('contato')->with('status', 'Sua mensagem foi recebida. Para atendimento imediato, fale conosco pelo WhatsApp.');
    }
}
