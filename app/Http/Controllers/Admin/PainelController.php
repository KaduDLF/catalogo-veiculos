<?php

namespace App\Http\Controllers\Admin;

use App\Models\Veiculo;
use App\Support\Loja;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PainelController
{
    public function dashboard(Request $request)
    {
        $filtros = $request->validate(['mes'=>'nullable|date_format:Y-m','periodo'=>'nullable|in:7,30,tudo']);
        $mes = $filtros['mes'] ?? now('America/Sao_Paulo')->format('Y-m');
        $inicio = CarbonImmutable::parse($mes.'-01');
        $periodo = $filtros['periodo'] ?? '7';
        $ranking = Veiculo::withCount(['visitas'=>fn ($q) => $q->when($periodo !== 'tudo', fn ($q) => $q->where('created_at','>=',now()->subDays((int)$periodo)))])
            ->orderByDesc('visitas_count')->orderBy('nome')->limit(5)->get()->where('visitas_count','>',0);
        return view('admin.dashboard', [
            'mes'=>$mes, 'periodo'=>$periodo, 'ranking'=>$ranking,
            'estoque'=>Veiculo::disponiveis()->count(),
            'preparacao'=>Veiculo::where('disponivel',false)->whereNull('data_venda')->count(),
            'vendidos'=>Veiculo::whereBetween('data_venda',[$inicio->toDateString(),$inicio->endOfMonth()->toDateString()])->count(),
            'recentes'=>Veiculo::latest('updated_at')->limit(5)->get(),
            'mensagens'=>DB::table('contato_mensagens')->whereNull('lida_em')->count(),
            'ultimasMensagens'=>DB::table('contato_mensagens')->latest()->limit(3)->get(),
        ]);
    }

    public function mensagens()
    {
        return view('admin.mensagens',['mensagens'=>DB::table('contato_mensagens')->latest()->paginate(15)]);
    }

    public function ler(int $id)
    {
        DB::table('contato_mensagens')->where('id',$id)->whereNull('lida_em')->update(['lida_em'=>now()]);
        return back();
    }

    public function configuracoes()
    {
        return view('admin.config',['loja'=>Loja::dados()]);
    }

    public function salvar(Request $request, string $aba)
    {
        if ($aba === 'conta') {
            $user = $request->user();
            $dados = $request->validate([
                'name'=>'required|string|max:120',
                'email'=>['required','email','lowercase','max:254',Rule::unique('users')->ignore($user)],
                'current_password'=>'required|current_password',
                'password'=>'nullable|string|min:12|confirmed',
            ]);
            unset($dados['current_password']);
            if (empty($dados['password'])) {
                unset($dados['password']);
            }
            $user->update($dados);
            $request->session()->regenerate();
        } else {
            $regras = match ($aba) {
                'loja'=>array_fill_keys(['nome','endereco','cidade','semana','sabado'],'required|string|max:200'),
                'canais'=>['telefone'=>['required','regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],'telefone2'=>['required','regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],'email'=>'required|email|max:254','instagram'=>'required|url:https|max:255'],
                'anuncio'=>['condicoes'=>'required|string|max:2000'],
                default=>abort(404),
            };
            $dados = $request->validate($regras);
            DB::transaction(function () use ($dados) {
                foreach ($dados as $campo=>$valor) {
                    DB::table('preferencias')->updateOrInsert(compact('campo'),compact('valor'));
                }
            });
        }
        return to_route('admin.config',['aba'=>$aba])->with('ok','Alterações salvas.');
    }
}
