# Proveniência e limites da demonstração

## Origem acadêmica

- data de preparação: **20/11/2026**;
- linguagem: PHP 8.3;
- framework: Laravel 13;
- banco padrão do demonstrador: SQLite;
- banco suportado por configuração: MySQL;
- produção acessada: não;
- dados empresariais utilizados: não.

O Core One empresarial serviu como referência funcional para o TCC. Seu repositório, banco de dados, credenciais, regras confidenciais e integrações não foram publicados.

## Natureza do código público

O projeto em `demo-executavel/` foi construído como uma implementação acadêmica autônoma. Ele reproduz o fluxo observável de navegação, consulta e fechamento sem afirmar que corresponde ao código operacional completo da empresa.

Os nomes, códigos, documentos, valores, associados, consultores, produções e boletos são fictícios e gerados deterministicamente. O repositório não inclui `.env`, bancos prontos, dumps, logs, uploads, tokens, senhas de ambientes hospedados ou registros de pessoas reais.

## Rastreabilidade

| Evidência | Localização pública |
|---|---|
| Regra de fechamento | `demo-executavel/app/Services/PaymentClosureService.php` |
| Estrutura do banco | `demo-executavel/database/migrations/` |
| Cenário fictício | `demo-executavel/database/seeders/DemoScenarioSeeder.php` |
| Testes automatizados | `demo-executavel/tests/Feature/` |
| Instalação e uso | `demo-executavel/README.md` |
| Execução contínua | `.github/workflows/demo-tests.yml` |

## Limites da verificação

O SQLite permite reproduzir as transições de estado, a persistência, os totais e a auditoria com instalação simples. A validação de características específicas de concorrência do MySQL exige um ambiente próprio e não é afirmada por este demonstrador.

O usuário e a senha descritos no README interno destinam-se exclusivamente à execução local. Um ambiente hospedado deve utilizar credenciais diferentes e compartilhadas fora do repositório.
