<?php

namespace Tests\Feature;

use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class CatalogoTest extends TestCase
{
    use RefreshDatabase;

    private function vehicle(array $attributes = []): Veiculo
    {
        return Veiculo::create(array_merge(['nome' => 'Honda Civic', 'marca' => 'Honda', 'modelo' => 'Civic', 'ano' => '2022/2022', 'km' => 43500, 'cambio' => 'Automático', 'preco' => 119900], $attributes));
    }

    public function test_catalog_filters_and_price_order(): void
    {
        $normal = $this->vehicle();
        $promo = $this->vehicle(['nome' => 'Honda City', 'modelo' => 'City', 'preco' => 130000, 'em_promocao' => true, 'preco_promocional' => 110000]);
        $this->vehicle(['nome' => 'Toyota Corolla', 'marca' => 'Toyota']);
        $this->vehicle(['nome' => 'Vendido', 'disponivel' => false]);
        $this->get('/veiculos?marca=Honda&ordem=menor&visualizacao=lista')->assertOk()
            ->assertSee('data-mode="lista"', false)->assertDontSee('Vendido')
            ->assertViewHas('veiculos', fn ($items) => $items->pluck('id')->all() === [$promo->id, $normal->id]);
        $this->get('/veiculos?filtro=promocoes')->assertOk()->assertViewHas('veiculos', fn ($items) => $items->total() === 1);
        $this->get('/veiculos?q=inexistente')->assertOk()->assertSee('Nenhum veículo encontrado');
    }

    public function test_details_and_unavailable_vehicles(): void
    {
        $vehicle = $this->vehicle(['descricao' => '<script>alert(1)</script>', 'fotos' => ['images/test-1.jpg', 'images/test-2.jpg']]);
        $this->get('/veiculos/'.$vehicle->id)->assertOk()->assertSee('Informações do veículo')
            ->assertSee('data-photo="1"', false)->assertSee('https://wa.me/5562985350837')
            ->assertDontSee('<script>alert(1)</script>', false);
        $vehicle->update(['disponivel' => false]);
        $this->get('/veiculos/'.$vehicle->id)->assertNotFound();
        $this->get('/veiculos/999')->assertNotFound();
    }

    public function test_pagination_keeps_filters_and_view_mode(): void
    {
        foreach (range(1, 12) as $i) {
            $this->vehicle();
        }
        $this->get('/veiculos?marca=Honda&visualizacao=lista')->assertOk()
            ->assertViewHas('veiculos', fn ($items) => $items->count() === 9 && $items->total() === 12)
            ->assertSee('visualizacao=lista');
    }
}
