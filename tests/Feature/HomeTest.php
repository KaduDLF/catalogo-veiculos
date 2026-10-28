<?php

namespace Tests\Feature;

use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function veiculo(array $attributes = []): Veiculo
    {
        return Veiculo::create(array_merge([
            'nome' => 'Honda Civic EXL',
            'ano' => '2022/2022',
            'km' => 43500,
            'cambio' => 'Automático',
            'preco' => 119900,
        ], $attributes));
    }

    public function test_sem_promocoes_mostra_oito_recentes_em_ordem(): void
    {
        $veiculos = collect(range(1, 10))->map(fn ($i) => $this->veiculo([
            'nome' => "Veículo $i",
            'created_at' => now()->subDays(11 - $i),
        ]));

        $this->get(route('home'))->assertOk()
            ->assertDontSee('id="promocoes"', false)
            ->assertSee('id="recentes"', false)
            ->assertViewHas('recentes', fn ($recentes) => $recentes->modelKeys() === $veiculos->reverse()->take(8)->pluck('id')->all());
    }

    public function test_promocao_exibe_precos_e_nao_repete_veiculo_nos_recentes(): void
    {
        foreach (range(1, 10) as $i) {
            $this->veiculo(['nome' => "Recente $i"]);
        }
        $promocao = $this->veiculo(['em_promocao' => true, 'preco_promocional' => 109900]);

        $response = $this->get('/')->assertOk()
            ->assertSee('id="promocoes"', false)
            ->assertSee('De R$ 119.900,00')->assertSee('Por R$ 109.900,00')
            ->assertViewHas('promocoes', fn ($items) => $items->modelKeys() === [$promocao->id])
            ->assertViewHas('recentes', fn ($items) => $items->count() === 7 && ! $items->contains('id', $promocao->id));

        $this->assertSame(1, substr_count($response->getContent(), 'data-veiculo-id="'.$promocao->id.'"'));
    }

    public function test_limita_promocoes_e_completa_com_recentes_sem_duplicar(): void
    {
        foreach (range(1, 12) as $i) {
            $this->veiculo(['em_promocao' => true, 'preco_promocional' => 100000]);
        }

        $response = $this->get('/')->assertOk();
        $promocoes = $response->viewData('promocoes');
        $recentes = $response->viewData('recentes');

        $this->assertCount(4, $promocoes);
        $this->assertCount(4, $recentes);
        $this->assertCount(0, $promocoes->intersect($recentes));
        $this->assertSame([12, 11, 10, 9], $promocoes->modelKeys());
    }

    public function test_precos_invalidos_e_flag_desligada_nao_ativam_promocao(): void
    {
        foreach ([null, 0, -1, 119900, 120000] as $preco) {
            $veiculo = $this->veiculo(['em_promocao' => true, 'preco_promocional' => $preco]);
            $this->assertFalse($veiculo->promocao_ativa);
        }
        $this->veiculo(['em_promocao' => false, 'preco_promocional' => 100000]);

        $this->get('/')->assertOk()
            ->assertDontSee('id="promocoes"', false)
            ->assertDontSee('vehicle-card__old-price', false)
            ->assertViewHas('recentes', fn ($items) => $items->count() === 6);
    }

    public function test_veiculos_indisponiveis_nao_aparecem(): void
    {
        $this->veiculo(['nome' => 'Vendido comum', 'disponivel' => false]);
        $veiculo = $this->veiculo([
            'nome' => 'Vendido em promoção', 'disponivel' => false,
            'em_promocao' => true, 'preco_promocional' => 100000,
        ]);
        $this->assertFalse($veiculo->promocao_ativa);

        $this->get('/')->assertOk()->assertDontSee('Vendido')
            ->assertDontSee('id="promocoes"', false)
            ->assertSee('Nenhum veículo disponível no momento.');
    }

    public function test_estoque_vazio_tem_mensagem_sem_cards_ficticios(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Nenhum veículo disponível no momento.')
            ->assertDontSee('id="promocoes"', false)
            ->assertDontSee('data-veiculo-id', false);
    }

    public function test_apenas_promocoes_nao_renderiza_secao_de_recentes_vazia(): void
    {
        $this->veiculo(['em_promocao' => true, 'preco_promocional' => 100000]);

        $this->get('/')->assertOk()->assertSee('id="promocoes"', false)
            ->assertDontSee('id="recentes"', false)
            ->assertDontSee('Nenhum veículo disponível');
    }
}
