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
                // Logika disposisi yang sudah ada (TETAP ADA)
                $unreadDispositionsCount = auth()->user()->dispositions()
                                     ->where('status', 'Terkirim')
                                     ->count();
                
                // Logika notifikasi baru (KITA TAMBAHKAN)
                $unreadNotifications = Auth::user()->unreadNotifications()->take(5)->get();

                // Kirim kedua variabel ke semua view
                $view->with('unreadDispositionsCount', $unreadDispositionsCount);
                $view->with('unreadNotifications', $unreadNotifications);
            }
        });
    }
}