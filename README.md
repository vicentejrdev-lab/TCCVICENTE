# Core One — demonstração acadêmica executável

Este repositório acompanha o TCC II de Engenharia de Software sobre o Core One, evolução do protótipo Gestão 360 para uma plataforma corporativa em PHP, Laravel e MySQL.

O conteúdo público não é uma cópia do sistema empresarial. A pasta [`demo-executavel`](demo-executavel) contém uma implementação acadêmica independente, sanitizada e reproduzível do fluxo apresentado no trabalho. Todos os nomes, documentos, valores e registros são fictícios.

## O que pode ser verificado

A demonstração permite:

- autenticar um usuário Master fictício;
- consultar o painel e os volumes do cenário de treinamento;
- navegar por consultores, produções, boletos e competências;
- visualizar a composição de um lote financeiro;
- fechar uma competência aberta;
- conferir a preservação dos valores, o checksum e o evento de auditoria;
- restaurar o cenário inicial com um comando.

O cenário determinístico contém 20 consultores, 170 produções, 300 associados, 45 boletos pendentes, uma competência e um lote com 20 itens agregados.

## Execução rápida

Requisitos: PHP 8.3 ou superior, Composer e a extensão SQLite do PHP.

```bash
cd demo-executavel
composer install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate:fresh --seed
php artisan serve
```

No Windows, o arquivo pode ser copiado com `copy .env.example .env`.

As credenciais locais são configuradas pelas variáveis `DEMO_MASTER_EMAIL` e `DEMO_MASTER_PASSWORD` do arquivo `.env`. Elas existem apenas para a instalação local e devem ser alteradas antes de qualquer hospedagem.

## Testes

```bash
cd demo-executavel
php artisan test
```

O workflow de integração contínua em `.github/workflows/demo-tests.yml` instala a aplicação com PHP 8.3 e executa a suíte automatizada em SQLite.

## Banco de dados

SQLite é o padrão do demonstrador porque reduz os passos de instalação. A aplicação também aceita MySQL por configuração no `.env`. O Core One empresarial utiliza MySQL; essa infraestrutura, suas credenciais, integrações e dados não fazem parte deste repositório.

## Documentação acadêmica

- [`IMPLEMENTACAO_E_VERIFICACAO.md`](IMPLEMENTACAO_E_VERIFICACAO.md): relação entre o código, o fechamento e os testes;
- [`PROVENIENCIA.md`](PROVENIENCIA.md): origem, limites e cuidados de sanitização;
- [`demo-executavel/README.md`](demo-executavel/README.md): instalação detalhada e roteiro de utilização.
