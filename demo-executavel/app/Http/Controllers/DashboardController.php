<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\Consultant;
use App\Models\FinancialPeriod;
use App\Models\PaymentLotEvent;
use App\Models\Production;
use App\Models\UnpaidBill;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $period = FinancialPeriod::query()
            ->with(['lots' => fn ($query) => $query->orderBy('code')])
            ->latest('starts_on')
            ->first();

        return view('dashboard', [
            'counts' => [
                'consultants' => Consultant::query()->count(),
                'productions' => Production::query()->count(),
                'associates' => Associate::query()->count(),
                'unpaidBills' => UnpaidBill::query()->where('status', 'pending')->count(),
            ],
            'period' => $period,
            'recentEvents' => PaymentLotEvent::query()
                ->with(['lot.period', 'user'])
                ->latest('created_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
