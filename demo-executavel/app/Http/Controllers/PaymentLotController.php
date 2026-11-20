<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClosePaymentLotRequest;
use App\Models\PaymentLot;
use App\Services\PaymentClosureService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentLotController extends Controller
{
    public function show(PaymentLot $lot): View
    {
        $lot->load([
            'period',
            'closedBy',
            'items' => fn ($query) => $query->with('consultant')->orderByDesc('official_amount'),
            'events' => fn ($query) => $query->with('user')->latest('created_at'),
        ]);

        return view('lots.show', ['lot' => $lot]);
    }

    public function close(
        ClosePaymentLotRequest $request,
        PaymentLot $lot,
        PaymentClosureService $service,
    ): RedirectResponse {
        try {
            $closedLot = $service->close(
                $lot,
                $request->user(),
                (string) $request->validated('reason'),
            );
        } catch (DomainException $exception) {
            return back()->withErrors(['lot' => $exception->getMessage()]);
        }

        return redirect()
            ->route('lots.show', $closedLot)
            ->with('success', 'Lote fechado com sucesso. Os valores e o evento de auditoria foram preservados.');
    }
}
