<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_configuracoes_e_mensagens_da_loja(): void
    {
        $this->actingAs(User::create(['name'=>'Loja', 'email'=>'loja@example.test', 'password'=>'senha-local-123456']));
        $this->put('/admin/configuracoes/canais', [
            'telefone'=>'(62) 99999-1234', 'telefone2'=>'(62) 99999-5678',
            'instagram'=>'https://www.instagram.com/loja/', 'email'=>'contato@example.test',
        ])->assertSessionHasNoErrors();
        $this->get('/contato')->assertOk()->assertSee('https://wa.me/5562999991234');
        $this->put('/admin/configuracoes/conta', [
            'name'=>'Outro', 'email'=>'novo@example.test', 'current_password'=>'invalida',
        ])->assertSessionHasErrors('current_password');
        $this->assertDatabaseMissing('users', ['email'=>'novo@example.test']);
        $this->post('/contato', ['nome'=>'Cliente', 'telefone'=>'62999999999', 'email'=>'teste@example.test',
            'assunto'=>'Financiamento', 'mensagem'=>'Tenho interesse neste veículo.'])->assertSessionHasNoErrors();
        $this->get('/admin')->assertViewHas('mensagens',1);
        $this->get('/admin/mensagens')->assertOk()->assertSee('Tenho interesse neste veículo.');
        $this->post('/admin/mensagens/1/lida')->assertRedirect();
        $this->get('/admin')->assertViewHas('mensagens',0);
    }

    public function test_acao_na_lista_registra_venda_na_data_local(): void
    {
        $veiculo = Veiculo::create([
            'nome' => 'Civic de teste', 'ano' => '2022/2022', 'km' => 20000,
            'cambio' => 'Automático', 'preco' => 75000, 'disponivel' => true,
            'em_promocao' => true, 'preco_promocional' => 73000,
        ]);
        $lista = route('admin.veiculos.index', ['status' => 'estoque']);
        $vender = route('admin.veiculos.vender', $veiculo);

        $this->patch($vender)->assertRedirect(route('login'));
        $this->actingAs(User::create(['name' => 'Loja', 'email' => 'loja@example.test', 'password' => 'senha-local-123456']));
        $this->travelTo(\Carbon\CarbonImmutable::parse('2026-11-08 02:00:00 UTC'));
        $this->get($lista)->assertOk()->assertSee('Marcar como vendido');
        $this->from($lista)->patch($vender)->assertRedirect($lista);
        $this->assertDatabaseHas('veiculos', [
            'id' => $veiculo->id, 'data_venda' => '2026-11-07', 'disponivel' => false,
            'em_promocao' => false, 'preco_promocional' => null,
        ]);
        $this->get(route('veiculos.show', $veiculo))->assertNotFound();
        $this->get(route('admin.veiculos.index', ['status' => 'vendido']))->assertOk()
            ->assertSee($veiculo->nome)->assertDontSee('Marcar como vendido');

        $this->travel(1)->days();
        $this->patch($vender)->assertSessionHas('ok', 'O veículo já está marcado como vendido.');
        $this->assertDatabaseHas('veiculos', ['id' => $veiculo->id, 'data_venda' => '2026-11-07']);
    }

    public function test_fluxo_de_cadastro_publicacao_visita_e_venda(): void
    {
        Storage::fake('public');
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Painel da Loja');
        $senha = 'Uma-senha-local-123';
        $this->artisan('admin:criar')->expectsQuestion('Nome','Loja')->expectsQuestion('E-mail','loja@example.test')
            ->expectsQuestion('Senha (mínimo 12 caracteres)',$senha)->expectsQuestion('Repita a senha',$senha)->assertSuccessful();
        $this->post('/admin/login',['email'=>'loja@example.test','password'=>'incorreta'])->assertSessionHasErrors('email');
        $this->post('/admin/login',['email'=>'loja@example.test','password'=>$senha])->assertRedirect('/admin');
        foreach (['/admin','/admin/veiculos','/admin/veiculos/create','/admin/mensagens','/admin/configuracoes'] as $url) {
            $this->get($url)->assertOk();
        }
        $dados = ['nome'=>'Honda de teste','marca'=>'Honda','modelo'=>'Civic','fabricacao'=>'2021','ano_modelo'=>'2022','preco'=>'105000',
            'km'=>23000,'cambio'=>'Automático','status'=>'estoque','em_promocao'=>'1','preco_promocional'=>'99000',
            'itens_confirmados'=>['Chave reserva', 'Tanque cheio', 'Película instalada']];
        $this->post('/admin/veiculos',array_replace($dados,['preco_promocional'=>106000]))->assertSessionHasErrors('preco_promocional');
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII=');
        $this->post('/admin/veiculos',$dados+['imagens'=>[UploadedFile::fake()->createWithContent('capa.png',$png)]])->assertSessionHasNoErrors()->assertRedirect('/admin/veiculos');
        $v = Veiculo::firstOrFail();
        Storage::disk('public')->assertExists(substr($v->foto,8));
        $primeiraFoto = $v->foto;
        $this->put('/admin/veiculos/'.$v->id, $dados + ['imagens' => [UploadedFile::fake()->createWithContent('segunda.png', $png)]])->assertSessionHasNoErrors();
        $this->assertCount(2, $v->fresh()->fotos);
        $this->put('/admin/veiculos/'.$v->id, $dados + ['remover_fotos' => [0]])->assertSessionHasNoErrors();
        $this->assertCount(1, $v->fresh()->fotos);
        $this->assertNotSame($primeiraFoto, $v->fresh()->foto);
        Storage::disk('public')->assertMissing(substr($primeiraFoto, 8));
        $this->assertSame($dados['itens_confirmados'], $v->itens_confirmados);
        $this->get('/admin/veiculos/'.$v->id.'/edit')->assertOk()->assertSee('Chave reserva')->assertSee('Tanque cheio')->assertSee('Película instalada');
        $this->withCookie(config('session.cookie'), session()->getId());
        $this->get('/veiculos/'.$v->id)->assertOk();
        $this->assertDatabaseCount('visitas',1);
        $this->post('/admin/sair')->assertRedirect('/admin/login');
        $this->withCookie(config('session.cookie'), \Illuminate\Support\Str::random(40));
        $this->get('/')->assertOk()->assertSee('Honda de teste');
        $this->get('/veiculos/'.$v->id)->assertOk();
        $this->get('/veiculos/'.$v->id)->assertOk();
        $this->assertDatabaseCount('visitas',2);
        $this->actingAs(User::firstOrFail());
        $this->put('/admin/veiculos/'.$v->id,array_replace($dados,['status'=>'vendido','data_venda'=>'2020-10-31']))->assertSessionHasNoErrors();
        $this->get('/veiculos/'.$v->id)->assertNotFound();
        $this->get('/admin?mes=2020-10')->assertViewHas('vendidos',1)->assertViewHas('ranking',fn ($ranking)=>$ranking->first()->visitas_count===2);
        $this->get('/admin?mes=2020-11')->assertViewHas('vendidos',0);
        $this->put('/admin/veiculos/'.$v->id,array_replace($dados,['status'=>'preparacao']))->assertSessionHasNoErrors();
        $this->get('/admin')->assertViewHas('preparacao',1);
        $this->assertNull($v->fresh()->data_venda);
        $this->delete('/admin/veiculos/'.$v->id)->assertRedirect();
        Storage::disk('public')->assertMissing(substr($v->foto,8));
    }
}
