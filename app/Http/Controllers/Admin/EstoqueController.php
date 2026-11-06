<?php

namespace App\Http\Controllers\Admin;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EstoqueController
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q'=>'nullable|string|max:120','status'=>'nullable|in:estoque,preparacao,vendido','mes'=>'nullable|date_format:Y-m']);
        $query = Veiculo::withCount('visitas')->latest()->orderByDesc('id');
        if ($filters['q'] ?? null) {
            $query->where('nome', 'like', '%'.$filters['q'].'%');
        }
        match ($filters['status'] ?? '') {
            'estoque' => $query->disponiveis(),
            'preparacao' => $query->where('disponivel', false)->whereNull('data_venda'),
            'vendido' => $query->whereNotNull('data_venda'),
            default => null,
        };
        if ($filters['mes'] ?? null) {
            $inicio = \Carbon\CarbonImmutable::parse($filters['mes'].'-01');
            $query->whereBetween('data_venda', [$inicio->toDateString(), $inicio->endOfMonth()->toDateString()]);
        }
        return view('admin.estoque', ['veiculos'=>$query->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.form', ['veiculo'=>new Veiculo(['disponivel'=>true])]);
    }

    public function edit(Veiculo $veiculo)
    {
        return view('admin.form', compact('veiculo'));
    }

    public function store(Request $request)
    {
        return $this->salvar($request, new Veiculo());
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        return $this->salvar($request, $veiculo);
    }

    private function salvar(Request $request, Veiculo $veiculo)
    {
        $dados = $request->validate([
            'nome'=>'required|string|max:255','marca'=>'required|string|max:255','modelo'=>'required|string|max:255',
            'versao'=>'nullable|string|max:255','fabricacao'=>'required|digits:4','ano_modelo'=>'required|digits:4',
            'km'=>'required|integer|min:0|max:2147483647','cambio'=>'required|string|max:100',
            'cor'=>'nullable|string|max:100','combustivel'=>['nullable','string',Rule::in($veiculo->combustiveisPermitidos())],'descricao'=>'nullable|string|max:15000',
            'preco'=>'required|numeric|min:0.01|max:9999999999.99|decimal:0,2',
            'status'=>'required|in:estoque,preparacao,vendido','data_venda'=>'exclude_unless:status,vendido|required|date_format:Y-m-d|before_or_equal:today',
            'em_promocao'=>'sometimes|boolean','preco_promocional'=>'exclude_unless:em_promocao,1|required|numeric|min:0.01|lt:preco|decimal:0,2',
            'itens_confirmados'=>'nullable|array|max:30','itens_confirmados.*'=>'required|string|max:100',
            'opcionais'=>'nullable|string|max:3000','imagens'=>'nullable|array|max:15','imagens.*'=>'required|image|mimes:jpeg,png,webp|max:5120',
            'capa'=>'nullable|integer|min:0',
        ]);
        $dados['ano'] = $dados['fabricacao'].'/'.$dados['ano_modelo'];
        $dados['disponivel'] = $dados['status'] === 'estoque';
        $dados['data_venda'] = $dados['data_venda'] ?? null;
        $dados['em_promocao'] = $dados['disponivel'] && $request->boolean('em_promocao');
        $dados['preco_promocional'] = $dados['em_promocao'] ? $dados['preco_promocional'] : null;
        $dados['itens_confirmados'] = array_values(array_unique(array_filter(array_map('trim', $dados['itens_confirmados'] ?? []))));
        $dados['opcionais'] = array_values(array_filter(array_map('trim', explode(',', $dados['opcionais'] ?? ''))));
        $novas = [];
        try {
            DB::transaction(function () use ($veiculo, $dados, $request, &$novas) {
                $registro = $veiculo->exists ? Veiculo::lockForUpdate()->findOrFail($veiculo->id) : $veiculo;
                $fotos = array_values(array_unique(array_filter([$registro->foto, ...($registro->fotos ?? [])])));
                $arquivos = $request->file('imagens', []);
                $total = count($fotos) + count($arquivos);
                $capa = (int) ($dados['capa'] ?? 0);
                if ($total > 15 || $capa >= max(1, $total)) {
                    throw ValidationException::withMessages(['imagens'=>'Confira a capa e o limite de 15 fotos por veículo.']);
                }
                foreach ($arquivos as $arquivo) {
                    $path = $arquivo->store('veiculos', 'public');
                    throw_unless($path, \RuntimeException::class, 'Falha ao armazenar foto.');
                    $novas[] = $path;
                    $fotos[] = 'storage/'.$path;
                }
                $registro->fill($dados);
                $registro->foto = $fotos[$capa] ?? null;
                $registro->fotos = $fotos;
                $registro->save();
            });
        } catch (\Throwable $error) {
            Storage::disk('public')->delete($novas);
            throw $error;
        }
        return to_route('admin.veiculos.index')->with('ok', 'Veículo salvo.');
    }

    public function destroy(Veiculo $veiculo)
    {
        $arquivos = collect([$veiculo->foto, ...($veiculo->fotos ?? [])])->filter(fn ($p) => str_starts_with($p ?? '', 'storage/veiculos/'))->unique();
        $veiculo->delete();
        foreach ($arquivos as $arquivo) {
            Storage::disk('public')->delete(substr($arquivo, 8));
        }
        return to_route('admin.veiculos.index')->with('ok', 'Veículo excluído.');
    }
}
