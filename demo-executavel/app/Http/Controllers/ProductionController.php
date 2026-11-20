<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $query = Production::query()
            ->with('consultant')
            ->when($search !== '', function ($builder) use ($search): void {
                $builder->where(function ($nested) use ($search): void {
                    $nested->where('external_code', 'like', "%{$search}%")
                        ->orWhereHas('consultant', function ($consultantQuery) use ($search): void {
                            $consultantQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('external_code', 'like', "%{$search}%");
                        });
                });
            });

        $summaryQuery = clone $query;
        $productions = $query->orderBy('external_code')->paginate(30)->withQueryString();

        return view('productions.index', [
            'productions' => $productions,
            'search' => $search,
            'summary' => [
                'count' => $summaryQuery->count(),
                'base' => (float) (clone $summaryQuery)->sum('gross_amount'),
                'bonus' => (float) (clone $summaryQuery)->sum('bonus_amount'),
            ],
        ]);
    }
}
