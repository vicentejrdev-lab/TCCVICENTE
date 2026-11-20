# Demonstrador acadêmico do Core One

Aplicação Laravel independente para reproduzir, com dados fictícios, a navegação e o fechamento financeiro descritos no TCC II.

## Requisitos

- PHP 8.3 ou superior;
- Composer;
- extensões `mbstring`, `openssl`, `pdo_sqlite`, `sqlite3`, `tokenizer` e `xml`.

Não é necessário instalar Node.js ou compilar recursos de frontend.

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate:fresh --seed
php artisan serve
```

No Windows:

```powershell
copy .env.example .env
```

A aplicação estará disponível em `http://127.0.0.1:8000`.

## Acesso local

O seeder cria um único usuário Master fictício. O login e a senha são definidos em `.env`:

```dotenv
DEMO_MASTER_EMAIL=master@coreone.invalid
DEMO_MASTER_PASSWORD=TCC-CoreOne-2026#
```

Essas credenciais são exclusivamente locais. Altere-as antes de hospedar a aplicação e nunca reutilize esse padrão em outro sistema.

## Roteiro de demonstração

1. Entre com o usuário Master local.
2. Confira no painel os volumes do cenário fictício.
3. Navegue por **Consultores**, **Produções** e **Boletos**.
4. Acesse **Competências** e abra a competência de novembro de 2026.
5. Abra o lote financeiro e confira seus 20 itens.
6. Informe uma justificativa e execute o fechamento.
7. Confira o novo estado, o responsável, o checksum e o histórico de auditoria.
8. Tente fechar novamente para observar a proteção contra repetição.

## Restaurar o cenário

```bash
php artisan demo:reset --force
```

O comando somente funciona quando `DEMO_MODE=true` e o ambiente é `local` ou `testing`.

## Testes

```bash
php artisan test
```

A suíte utiliza SQLite em memória e não acessa produção nem serviços externos.

## MySQL opcional

Para utilizar MySQL, altere o `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coreone_demo
DB_USERNAME=usuario_local
DB_PASSWORD=senha_local
```

Crie um banco vazio e execute `php artisan migrate:fresh --seed`. Não reutilize banco ou credenciais do Core One empresarial.

## Limites

Este projeto é um recorte acadêmico escrito para reprodução pública. Ele não contém o código-fonte integral, integrações, configurações, credenciais ou dados do sistema empresarial.
