<?php

namespace App\Http\Controllers;

use App\Models\FinancialPeriod;
use Illuminate\View\View;

class FinancialPeriodController extends Controller
{
    public function index(): View
    {
        $periods = FinancialPeriod::query()
            ->withCount('lots')
            ->withSum('lots as official_total', 'official_amount')
            ->latest('starts_on')
            ->get();

        return view('periods.index', ['periods' => $periods]);
    }

    public function show(FinancialPeriod $period): View
    {
        $period->load([
            'lots' => fn ($query) => $query->withCount('items')->orderBy('code'),
            'closedBy',
        ]);

        return view('periods.show', ['period' => $period]);
    }
}
