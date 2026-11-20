<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultantController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $profile = trim((string) $request->string('profile'));

        $consultants = Consultant::query()
            ->withCount(['productions', 'associates'])
            ->withSum('lotItems as payment_total', 'official_amount')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested->where('full_name', 'like', "%{$search}%")
                        ->orWhere('external_code', 'like', "%{$search}%");
                });
            })
            ->when($profile !== '', fn ($query) => $query->where('profile', $profile))
            ->orderBy('external_code')
            ->paginate(20)
            ->withQueryString();

        return view('consultants.index', [
            'consultants' => $consultants,
            'search' => $search,
            'profile' => $profile,
        ]);
    }
}
