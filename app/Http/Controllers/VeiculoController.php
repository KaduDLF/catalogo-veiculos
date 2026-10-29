<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VeiculoController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:100', 'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100', 'filtro' => 'nullable|in:promocoes',
            'ordem' => 'nullable|in:recentes,menor,maior', 'visualizacao' => 'nullable|in:grade,lista',
        ]);
        $query = Veiculo::disponiveis();
        if ($q = $filters['q'] ?? null) {
            $query->where('nome', 'like', '%'.$q.'%');
        }
        foreach (['marca', 'modelo'] as $campo) {
            if ($valor = $filters[$campo] ?? null) {
                $query->where($campo, $valor);
            }
        }
        if (($filters['filtro'] ?? '') === 'promocoes') {
            $query->emPromocao();
        }
        $ordem = $filters['ordem'] ?? 'recentes';
        if (in_array($ordem, ['menor', 'maior'])) {
            $query->orderByRaw('CASE WHEN em_promocao = true AND preco_promocional > 0 AND preco_promocional < preco THEN preco_promocional ELSE preco END '.($ordem === 'menor' ? 'asc' : 'desc'));
        }
        $veiculos = $query->latest()->orderByDesc('id')->paginate(9)->withQueryString();
        $marcas = Veiculo::disponiveis()->whereNotNull('marca')->distinct()->orderBy('marca')->pluck('marca');
        $modelos = Veiculo::disponiveis()->when($filters['marca'] ?? null, fn ($q, $marca) => $q->where('marca', $marca))
            ->whereNotNull('modelo')->distinct()->orderBy('modelo')->pluck('modelo');

        return view('veiculos.index', compact('veiculos', 'marcas', 'modelos', 'filters'));
    }

    public function show(Veiculo $veiculo): View
    {
        abort_unless($veiculo->disponivel, 404);
        $fotos = collect([$veiculo->foto, ...($veiculo->fotos ?? [])])->filter()->unique()->values();

        return view('veiculos.show', compact('veiculo', 'fotos'));
    }
}
