# Catálogo de Veículos

Projeto Laravel para catálogo online de veículos da L2 Veículos.

## Executar o projeto

Requer PHP 8.3+, Composer e PostgreSQL com o banco criado.

```powershell
composer install
Copy-Item .env.example .env
# Configure DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD no .env.
php artisan key:generate
php artisan migrate
php artisan serve
```

Em uma instalação existente, preserve o `.env` e a chave atuais e execute
`php artisan migrate` antes de acessar a Home atualizada.

## Veículos na Home

A Home consulta a tabela `veiculos`, sem veículos fictícios:

- Mostra até quatro veículos em promoção, ordenados por cadastro mais recente
  (`created_at`, com desempate por `id`).
- Completa até oito cards com os veículos disponíveis mais recentes, excluindo
  os IDs já exibidos nas promoções. Promoções além do limite podem aparecer
  entre os recentes, também com seu preço promocional.
- Sem promoções válidas, a seção de promoções não é renderizada e os recentes
  ocupam as oito vagas. Sem estoque, uma mensagem é exibida.
- `disponivel = false` retira o veículo de ambas as seções.
- Uma promoção exige `em_promocao = true` e `preco_promocional > 0` e menor
  que `preco`. Valores nulos, iguais ou maiores não ativam a promoção.
- O card promocional mostra selo, preço original riscado e preço promocional.
  A grade, as cores, o cabeçalho e o rodapé existentes foram preservados.

O modelo `App\Models\Veiculo` permite cadastrar `nome`, `ano` (por exemplo,
`2022/2022`), `km` (inteiro), `cambio`, `preco`, `foto` (caminho relativo a
`public/`, opcional), `disponivel`, `em_promocao` e `preco_promocional`.
Preços devem ser informados como números decimais, sem `R$` ou separadores de
milhar. Sem foto, o card mantém o espaço reservado atual.

Exemplo para um veículo já cadastrado, usando `php artisan tinker`:

```php
$veiculo = App\Models\Veiculo::findOrFail(1);
$veiculo->update(['em_promocao' => true, 'preco_promocional' => 109900.00]);
// Para encerrar a promoção:
$veiculo->update(['em_promocao' => false, 'preco_promocional' => null]);
```

Não foi adicionado um campo `destaque`: a seleção solicitada usa promoção e
data de cadastro. O projeto ainda não possui painel administrativo de cadastro.

## Testes

Os testes usam SQLite em memória, sem acessar o banco configurado no `.env`.
É necessário habilitar a extensão PHP `pdo_sqlite`.

```powershell
php vendor/phpunit/phpunit/phpunit
```

Se a extensão estiver instalada mas desabilitada:

```powershell
php -d extension=pdo_sqlite vendor/phpunit/phpunit/phpunit
```

Cobertura: ausência de promoções, reposição por recentes, ordem, limites,
ausência de duplicatas, preços inválidos, indisponibilidade e estoque vazio.

## Catálogo e detalhes

- `/veiculos`: filtros por marca, modelo e promoção; busca pelo nome; ordenação
  por data ou preço efetivo; paginação de nove veículos e alternância grade/lista.
- `/veiculos/{id}`: informações, descrição, opcionais, galeria e contato pelo
  WhatsApp. Veículos indisponíveis retornam 404.
- Header e footer compartilhados em `resources/views/layouts/app.blade.php`.
- Execute `php artisan migrate` para adicionar marca, modelo, versão, cor,
  combustível, descrição, opcionais e fotos. `fotos` e `opcionais` são arrays;
  os caminhos de fotos são relativos a `public/`. A galeria inclui a foto principal.
- Para completar os exemplos locais: `php artisan db:seed --class=VeiculosTesteSeeder`.
  Fotos ausentes mantêm o espaço reservado; miniaturas e navegação aparecem
  conforme as fotos cadastradas. Empresa e Contato ainda aguardam seus layouts.

## Empresa e Contato

- `/empresa`: apresentação da loja. A galeria aguarda as fotos originais: adicione
  arquivos em `public/images/empresa` e entradas `src`/`alt` em `config/empresa.php`.
- `/contato`: formulário validado, proteção CSRF e limite de cinco envios por minuto.
  As mensagens são gravadas em `contato_mensagens`; não há envio de e-mail nem
  tela administrativa de leitura nesta etapa. O botão WhatsApp permite contato direto.
- Execute `php artisan migrate` ao atualizar a instalação para criar a tabela de mensagens.
