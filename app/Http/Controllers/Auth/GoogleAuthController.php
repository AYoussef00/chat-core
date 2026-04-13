<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse|SymfonyRedirect
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return redirect()
                ->route('login')
                ->with('status', 'Session expired. Please try signing in with Google again.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->with('status', 'Could not sign in with Google. Please try again.');
        }

        $defaultCompanyId = DB::table('companies')->value('id');
        if ($defaultCompanyId === null) {
            abort(500, 'No company is configured. Run migrations.');
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->first();

        if (! $user) {
            $user = User::query()
                ->where('company_id', $defaultCompanyId)
                ->where('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            }
        }

        if (! $user) {
            $name = $googleUser->getName()
                ?: ($googleUser->user['given_name'] ?? null)
                ?: explode('@', (string) $googleUser->getEmail())[0];

            $user = User::query()->create([
                'company_id' => $defaultCompanyId,
                'name' => $name,
                'email' => $googleUser->getEmail(),
                'password' => null,
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'status' => 'active',
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
