<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class Loja
{
    const PADRAO = [
        'nome' => 'L2 Veículos', 'endereco' => 'Rua Frutuoso Alves Guimarães',
        'cidade' => 'Centro - Rialma - GO', 'semana' => 'Segunda a sexta: 8h às 18h',
        'sabado' => 'Sábado: 8h às 12h', 'telefone' => '(62) 98535-0837',
        'telefone2' => '(62) 99641-3235', 'email' => 'l2veiculos@gmail.com',
        'instagram' => 'https://www.instagram.com/l2veiculosrialma/',
        'condicoes' => "✅ Aceitamos trocas\n💳 Parcelamos no cartão em até 18x\n🏦 Facilitamos o seu financiamento!",
    ];

    public static function dados(): array
    {
        return once(fn () => array_replace(self::PADRAO, DB::table('preferencias')->pluck('valor', 'campo')->all()));
    }

    public static function whatsapp(string $campo = 'telefone'): string
    {
        return 'https://wa.me/55'.preg_replace('/\D/', '', self::dados()[$campo]);
    }
}
