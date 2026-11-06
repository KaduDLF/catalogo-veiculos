<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class NovoContato extends Mailable
{
    public function __construct(public readonly array $dados)
    {
    }

    public function build(): static
    {
        return $this->subject('Novo contato pelo site: '.$this->dados['assunto'])
            ->replyTo($this->dados['email'], $this->dados['nome'])
            ->text('emails.contato');
    }
}
