<?php

namespace App\Http\Controllers\Onboarding;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingStrategyController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('OnboardingStrategy', [
            'accountFor' => $request->session()->get('onboarding.account_for'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_for' => ['required', 'string', 'in:client,employer,self'],
        ]);

        $request->session()->put('onboarding.account_for', $validated['account_for']);

        return redirect()->route('onboarding.account.about');
    }
}

