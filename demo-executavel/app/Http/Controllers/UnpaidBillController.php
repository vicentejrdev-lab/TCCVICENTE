<?php

namespace App\Http\Controllers;

use App\Models\UnpaidBill;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnpaidBillController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $status = trim((string) $request->string('status', 'pending'));

        $query = UnpaidBill::query()
            ->with(['associate', 'consultant'])
            ->when($status !== '', fn ($builder) => $builder->where('status', $status))
            ->when($search !== '', function ($builder) use ($search): void {
                $builder->where(function ($nested) use ($search): void {
                    $nested->where('reference', 'like', "%{$search}%")
                        ->orWhereHas('associate', fn ($associateQuery) => $associateQuery->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('consultant', fn ($consultantQuery) => $consultantQuery->where('full_name', 'like', "%{$search}%"));
                });
            });

        $summaryQuery = clone $query;
        $bills = $query->orderBy('due_date')->paginate(25)->withQueryString();

        return view('unpaid-bills.index', [
            'bills' => $bills,
            'search' => $search,
            'status' => $status,
            'summary' => [
                'count' => $summaryQuery->count(),
                'amount' => (float) (clone $summaryQuery)->sum('amount'),
                'withPdf' => (clone $summaryQuery)->where('has_pdf', true)->count(),
            ],
        ]);
    }
}
