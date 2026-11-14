# Core One — evidências acadêmicas de implementação e verificação

Este repositório apresenta um recorte acadêmico do Core One, sistema que evoluiu do MVP Gestão 360 para uma aplicação corporativa desenvolvida em PHP/Laravel com MySQL.

O material foi preparado para a subseção **4.4 — Implementação e verificação** do TCC II de Engenharia de Software. O objetivo é demonstrar, por meio de código e testes reais, como o fechamento das premiações controla concorrência, valida o estado do lote, registra a aprovação e aplica a confirmação dupla.

## Conteúdo

- `codigo/PaymentClosureService.closeLot.php`: método responsável pelo fechamento integral do lote;
- `codigo/FinancialApprovalPolicyService.requiresIndependentApproval.php`: leitura da configuração de confirmação dupla;
- `codigo/PaymentClosureService.assertIndependentActor.php`: bloqueio do fechamento pelo mesmo usuário que preparou o lote;
- `testes/CoreOneSprint22PaymentClosureTest.recortes.md`: dois testes diretamente relacionados às regras apresentadas;
- `testes/resultados/`: resultados PHPUnit sanitizados;
- `IMPLEMENTACAO_E_VERIFICACAO.md`: relatório acadêmico consolidado;
- `PROVENIENCIA.md`: origem, hashes e condições da verificação.

## Resultado verificado

Os dois testes selecionados foram executados em **14/11/2026**, com PHP 8.3.31 e SQLite em memória.

| Teste | Resultado | Asserções |
|---|---:|---:|
| Separação entre fechamento, envio e confirmação bancária | Aprovado | 14 |
| Confirmação dupla com outro usuário Master | Aprovado | 4 |

## Limites do recorte

Este não é o repositório operacional completo do Core One. Foram publicados somente os métodos, testes e resultados necessários à demonstração acadêmica. Não estão incluídos dados empresariais, credenciais, arquivos `.env`, dumps, registros de consultores ou regras não relacionadas ao TCC.

Os arquivos de código são recortes literais da versão local analisada. Como dependem da aplicação completa, não podem ser executados isoladamente a partir deste repositório.
