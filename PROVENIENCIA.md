# Proveniência das evidências

## Origem analisada

- Data da análise e dos testes: **14/11/2026**;
- branch local: `codex/coreone-pre-finance-governance`;
- HEAD do repositório operacional: `590df22c187b5d45ca9838c7c4dd5e11a913404e`;
- PHP usado nos testes: `8.3.31`;
- banco de teste: SQLite em memória;
- produção acessada: não;
- migrations em banco persistente: não.

## Repositório acadêmico público

- URL confirmada: `https://github.com/vicentejrdev-lab/TCCVICENTE`;
- visibilidade verificada: pública;
- branch publicada: `main`;
- conteúdo publicado: somente o recorte acadêmico sanitizado descrito neste repositório.

O repositório operacional completo do Core One não foi publicado.

## Situação Git dos arquivos na origem

| Arquivo | Situação durante a análise |
|---|---|
| `app/Services/PaymentClosureService.php` | não rastreado |
| `app/Services/FinancialApprovalPolicyService.php` | rastreado e sem alteração local |
| `tests/Feature/Database/CoreOneSprint22PaymentClosureTest.php` | não rastreado |

## Hashes SHA-256 da origem

| Arquivo | SHA-256 |
|---|---|
| `PaymentClosureService.php` | `11D4B2F34E3E22CBE8CD65CA4E3B73072470C80D833564D01814CE1AD3E219A5` |
| `FinancialApprovalPolicyService.php` | `8793E7DF299570EE9A14223D5A10CC7769C5716E0E11B6F11C326009CF842770` |
| `CoreOneSprint22PaymentClosureTest.php` | `274605F74395A92E765985C424318AA81D4B4A5BAF5E452BA23F7CFA25FBB142` |

## Recortes publicados

- `PaymentClosureService::closeLot`: linhas 268–323 da origem;
- `FinancialApprovalPolicyService::requiresIndependentApproval`: linhas 19–30;
- `PaymentClosureService::assertIndependentActor`: linhas 1003–1008;
- teste das três etapas financeiras: linhas 261–295;
- teste da confirmação dupla: linhas 388–406.

Os XMLs de resultado tiveram somente os caminhos absolutos locais substituídos pelo caminho relativo do arquivo de teste. Nomes dos testes, quantidade de asserções, duração, erros e falhas foram preservados.
