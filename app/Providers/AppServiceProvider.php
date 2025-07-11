<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- Tambahkan ini
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use App\Models\Disposition; // <-- Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { /* ... */ }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadDispositionsCount = Disposition::where('to_user_id', Auth::id())
                                                      ->where('status', 'Terkirim')
                                                      ->count();
                $view->with('unreadDispositionsCount', $unreadDispositionsCount);
            }
        });
    }
}