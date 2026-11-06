<?php

namespace Tests\Feature;

use App\Mail\NovoContato;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Mail;

class InstitucionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_paginas_e_links(): void
    {
        $this->get('/empresa')->assertOk()->assertSee('Quem')->assertSee(route('contato'));
        $this->get('/contato')->assertOk()->assertSee('Fale conosco')->assertSee(route('empresa'));
    }

    public function test_mensagem_valida_e_salva(): void
    {
        Mail::fake();
        config()->set('contato.notification_email', 'recepcao@example.com');
        $dados = ['nome' => 'Cliente Teste', 'email' => 'teste@example.com', 'telefone' => '(62) 99999-9999', 'assunto' => 'Financiamento', 'mensagem' => 'Gostaria de conhecer as condições de financiamento.'];
        $this->post('/contato', $dados)->assertRedirect(route('contato'))->assertSessionHas('status');
        $this->assertDatabaseHas('contato_mensagens', $dados);
        Mail::assertSent(NovoContato::class, function (NovoContato $mail): bool {
            return $mail->hasTo('recepcao@example.com')
                && $mail->dados['email'] === 'teste@example.com';
        });
    }

    public function test_mensagem_invalida_nao_e_salva(): void
    {
        $this->from('/contato')->post('/contato', ['email' => 'invalido'])->assertRedirect('/contato')->assertSessionHasErrors(['nome', 'email', 'telefone', 'assunto', 'mensagem']);
        $this->assertDatabaseCount('contato_mensagens', 0);
    }
}
