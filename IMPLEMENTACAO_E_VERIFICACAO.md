# 4.4 — Implementação e verificação

O fechamento das premiações é executado pelo método `closeLot`, da classe `PaymentClosureService`. A operação ocorre dentro de uma transação e relê o lote com bloqueio pessimista. Esse procedimento impede que duas solicitações concorrentes alterem simultaneamente o mesmo fechamento. Antes de gravar o resultado, o serviço verifica a janela de fechamento, a situação do lote, a independência do aprovador, a atualidade dos cálculos e a integridade dos itens.

Quando as verificações são atendidas, os itens em preparação recebem o estado `closed`. O lote registra o usuário responsável pela aprovação, a data, a justificativa, o checksum da aprovação e o incremento da versão de bloqueio. Na continuidade do método, a competência também é fechada e são produzidos registros no histórico de eventos e no livro-razão financeiro.

Os recursos `DB::transaction`, Eloquent e `lockForUpdate` são fornecidos pelo Laravel. Já os estados financeiros, a confirmação dupla, os checksums, a separação entre preparação e aprovação e os eventos de auditoria constituem regras específicas do Core One.

## Confirmação dupla

A classe `FinancialApprovalPolicyService` consulta a configuração `financial.dual_approval`. Quando a opção está habilitada — ou quando a configuração ainda não existe, pois o padrão é ativo — o método `assertIndependentActor` impede que o responsável pela preparação também finalize o lote. Nesse caso, outra conta com as permissões exigidas deve realizar o fechamento.

## Verificação por testes

Foram selecionados dois testes da classe `CoreOneSprint22PaymentClosureTest`:

1. `test_closure_payment_submission_and_bank_confirmation_are_three_separate_steps`: verifica que fechar o lote, enviá-lo para pagamento e registrar a confirmação bancária são operações diferentes. O teste também confirma que não é possível registrar o pagamento bancário antes do envio.
2. `test_dual_approval_requires_another_master_to_close`: verifica que o próprio preparador é bloqueado quando a confirmação dupla está ativa e que uma segunda conta Master consegue concluir o fechamento, preservando os identificadores de quem preparou e de quem aprovou.

Os dois testes foram executados em ambiente isolado com SQLite em memória. O primeiro concluiu 14 asserções e o segundo, 4 asserções, sem erros ou falhas. Os XMLs sanitizados estão em `testes/resultados`.

## Observação de rastreabilidade

No repositório operacional analisado, `PaymentClosureService.php` e o arquivo de teste ainda estavam fora do histórico Git. Por essa razão, este repositório acadêmico registra uma cópia verificável dos recortes e seus hashes de origem, mas não afirma que esses arquivos pertenciam ao commit operacional indicado em `PROVENIENCIA.md`.
