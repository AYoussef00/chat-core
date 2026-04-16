<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingPersonRoleController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('OnboardingPersonRole', [
            'personRole' => $request->session()->get('onboarding.person_role'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'person_role' => ['required', 'string', 'in:coach,freelancer,consultant,creator,media,public_figure'],
        ]);

        $request->session()->put('onboarding.person_role', $validated['person_role']);

        return redirect()->route('dashboard');
    }
}

