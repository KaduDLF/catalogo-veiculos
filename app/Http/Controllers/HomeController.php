<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\View\View;

class HomeController
{
    public function __invoke(): View
    {
        $promocoes = Veiculo::disponiveis()->emPromocao()
            ->latest()->orderByDesc('id')->limit(4)->get();

        $recentes = Veiculo::disponiveis()
            ->whereNotIn('id', $promocoes->modelKeys())
            ->latest()->orderByDesc('id')
            ->limit(8)->get();

        return view('home.index', compact('promocoes', 'recentes'));
    }
}
