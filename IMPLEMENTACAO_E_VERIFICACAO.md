# 4.3 — Implementação e verificação

O demonstrador acadêmico concentra a regra de fechamento em `demo-executavel/app/Services/PaymentClosureService.php`. O serviço recebe o lote, o usuário Master responsável e a justificativa informada na interface. A operação é executada dentro de uma transação e relê o lote com `lockForUpdate`, reduzindo o risco de alterações concorrentes durante a gravação.

Antes do fechamento, o serviço verifica a situação do lote, a existência dos itens e a consistência dos valores. Os totais são recalculados no servidor a partir dos 20 itens agregados. Em seguida, os itens e o lote passam para a situação fechada, a versão é incrementada e são armazenados o responsável, a data, a justificativa e um checksum determinístico da composição financeira.

O fechamento também cria um evento de auditoria com o estado anterior e o estado posterior. Quando todos os lotes da competência estão fechados, a competência recebe a mesma situação. Uma nova tentativa sobre o mesmo lote é rejeitada sem alterar valores, versão ou histórico.

`DB::transaction`, Eloquent e `lockForUpdate` são recursos do Laravel. A composição do lote, suas situações, o checksum, a preservação dos valores e o registro de auditoria representam as regras implementadas no demonstrador do Core One.

## Cenário fictício

`demo-executavel/database/seeders/DemoScenarioSeeder.php` cria sempre o mesmo conjunto de dados: 20 consultores, 170 produções, 300 associados, 45 boletos pendentes, uma competência aberta, um lote aberto e 20 itens financeiros. O banco não contém dados empresariais, credenciais de produção ou integrações externas.

## Verificação automatizada

A suíte em `demo-executavel/tests/Feature` verifica:

- autenticação e proteção das telas internas;
- volumes determinísticos do cenário;
- exibição dos indicadores no painel;
- fechamento do lote pelo usuário Master;
- preservação da soma dos itens;
- geração de checksum e evento de auditoria;
- fechamento da competência;
- rejeição de uma segunda tentativa de fechamento;
- restauração do cenário fictício pelo comando `demo:reset`.

Os testes utilizam SQLite em memória e não acessam produção, serviços externos ou bancos persistentes.
