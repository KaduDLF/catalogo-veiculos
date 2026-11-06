<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    public const ITENS = ['Chave reserva', 'Manual do proprietário', 'Veículo revisado'];

    public function combustiveisPermitidos(): array
    {
        return array_values(array_unique(array_filter(['Flex', 'Gasolina', 'Etanol', 'Diesel', $this->combustivel])));
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    public function getStatusAttribute(): string
    {
        return $this->disponivel ? 'Disponível' : ($this->data_venda ? 'Vendido' : 'Em preparação');
    }

    protected $fillable = [
        'data_venda', 'itens_confirmados',
        'nome', 'ano', 'km', 'cambio', 'preco', 'foto',
        'disponivel', 'em_promocao', 'preco_promocional',
        'marca', 'modelo', 'versao', 'cor', 'combustivel', 'descricao', 'opcionais', 'fotos',
    ];

    protected function casts(): array
    {
        return [
            'opcionais' => 'array',
            'itens_confirmados' => 'array',
            'fotos' => 'array',
            'km' => 'integer',
            'preco' => 'decimal:2',
            'preco_promocional' => 'decimal:2',
            'disponivel' => 'boolean',
            'em_promocao' => 'boolean',
        ];
    }

    public function scopeDisponiveis(Builder $query): Builder
    {
        return $query->where('disponivel', true);
    }

    public function getWhatsappUrlAttribute(): string
    {
        return \App\Support\Loja::whatsapp().'?text='.rawurlencode('Olá! Tenho interesse no veículo '.$this->nome.'. '.route('veiculos.show', $this));
    }

    public function scopeEmPromocao(Builder $query): Builder
    {
        return $query->where('em_promocao', true)
            ->where('preco_promocional', '>', 0)
            ->whereColumn('preco_promocional', '<', 'preco');
    }

    public function getPromocaoAtivaAttribute(): bool
    {
        return $this->disponivel && $this->em_promocao
            && $this->preco_promocional > 0
            && $this->preco_promocional < $this->preco;
    }
}
