<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingAccountAboutController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('OnboardingAccountAbout', [
            'accountAbout' => $request->session()->get('onboarding.account_about'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_about' => ['required', 'string', 'in:business,person,other'],
        ]);

        $request->session()->put('onboarding.account_about', $validated['account_about']);

        return redirect()->route('dashboard');
    }
}

