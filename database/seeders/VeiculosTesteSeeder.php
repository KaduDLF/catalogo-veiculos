<?php

namespace Database\Seeders;

use App\Models\Veiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VeiculosTesteSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new \RuntimeException('Os veículos fictícios são apenas para teste local.');
        }

        $veiculos = [
            ['Honda Civic EXL', '2022/2022', 43500, 'Automático', 119900, 109900],
            ['Toyota Corolla XEi', '2021/2022', 52000, 'Automático', 125900, 118900],
            ['Jeep Renegade Longitude', '2020/2021', 61000, 'Automático', 89900, 84900],
            ['Hyundai HB20 Comfort', '2023/2023', 24000, 'Manual', 72900, 68900],
            ['Chevrolet Onix LT', '2022/2023', 32000, 'Manual', 69900, null],
            ['Volkswagen T-Cross Highline', '2023/2024', 18000, 'Automático', 139900, null],
            ['Fiat Argo Drive', '2021/2022', 48000, 'Manual', 59900, null],
            ['Nissan Kicks Advance', '2022/2023', 35000, 'Automático', 104900, null],
            ['Renault Duster Iconic', '2021/2022', 57000, 'Automático', 87900, null],
            ['Honda HR-V EX', '2020/2021', 68000, 'Automático', 99900, null],
            ['Fiat Strada Freedom', '2023/2024', 15000, 'Manual', 112900, null],
            ['Toyota Hilux SRV', '2021/2022', 76000, 'Automático', 229900, null],
        ];

        DB::transaction(function () use ($veiculos) {
            foreach ($veiculos as $index => [$nome, $ano, $km, $cambio, $preco, $promocional]) {
                $veiculo = Veiculo::firstOrCreate(['nome' => $nome.' (Teste)'], [
                    'ano' => $ano,
                    'km' => $km,
                    'cambio' => $cambio,
                    'preco' => $preco,
                    'disponivel' => true,
                    'em_promocao' => $promocional !== null,
                    'preco_promocional' => $promocional,
                    'created_at' => now()->subDays($index),
                ]);
                $veiculo->update([
                    'marca' => $veiculo->marca ?? explode(' ', $nome)[0],
                    'modelo' => $veiculo->modelo ?? explode(' ', $nome)[1],
                    'cor' => $veiculo->cor ?? ['Prata', 'Branco', 'Preto'][$index % 3],
                    'combustivel' => $veiculo->combustivel ?? ($index === 11 ? 'Diesel' : 'Flex'),
                    'descricao' => $veiculo->descricao ?? 'Veículo fictício para demonstração do catálogo e da página de detalhes.',
                    'opcionais' => $veiculo->opcionais ?? ['Ar-condicionado', 'Multimídia', 'Freios ABS', 'Direção elétrica'],
                ]);
            }
        });
    }
}
