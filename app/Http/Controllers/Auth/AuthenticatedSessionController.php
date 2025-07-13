<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
// Hapus 'use App\Providers\RouteServiceProvider;' jika ada
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the SKBT login view.
     */
    public function createSkbtLogin(): View // <-- TAMBAHKAN METHOD INI
    {
        return view('auth.login-skbt');
    }

    /**
     * Display the external login view.
     */
    public function createExternal(): View  // <-- TAMBAHKAN METHOD INI
    {
        return view('auth.login-external');
    }
    
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // Pengecekan baru yang tidak sensitif terhadap huruf besar/kecil dan spasi
        if ($user->role && strtolower(trim($user->role->name)) === 'klien eksternal') {
            return redirect()->route('client.dashboard');
        }

        // Untuk role lainnya (internal), arahkan ke dashboard admin
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}