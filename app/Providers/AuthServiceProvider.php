<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\Disposition;
use App\Models\User;
use App\Policies\DocumentPolicy;
use App\Policies\DispositionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Document::class => DocumentPolicy::class,
        Disposition::class => DispositionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Aturan #1: Kunci Master HANYA untuk Super Admin.
        // Pengecekan ini sekarang menggunakan sistem role bawaan Anda.
        Gate::before(function (User $user) {
            if ($user->role && $user->role->name === 'Super Admin') {
                return true;
            }
        });
        
        // --- DEFINISI SEMUA IZIN APLIKASI ---

        // Aturan untuk level admin (Super Admin otomatis lolos karena Gate::before)
        Gate::define('isAdmin', function (User $user) {
            return $user->role && in_array($user->role->name, ['Admin Arsip']);
        });
        
        // Aturan untuk Pejabat Struktural
        Gate::define('isStructural', function (User $user) {
             return $user->role && $user->role->name === 'Pejabat Struktural';
        });

        // Aturan untuk pengguna Klien Eksternal (OPD/Desa)
        Gate::define('isClient', function (User $user) {
            return $user->role && $user->role->name === 'Klien Eksternal';
        });

        // Aturan untuk pengguna Pemohon SKBT
        Gate::define('isApplicant', function (User $user) {
            return $user->role && $user->role->name === 'Pemohon';
        });

        // Aturan untuk semua pengguna Internal
        Gate::define('isInternalUser', function (User $user) {
            return $user->role && !in_array($user->role->name, ['Klien Eksternal', 'Pemohon']);
        });

        // Aturan untuk yang bisa membuat disposisi
        Gate::define('can-disposition', function (User $user) {
            return $user->role && in_array($user->role->name, ['Admin Arsip', 'Pejabat Struktural', 'Auditor']);
        });

        // Aturan untuk yang bisa melihat statistik di dashboard
        Gate::define('view-admin-stats', function (User $user) {
            return $user->role && in_array($user->role->name, ['Admin Arsip', 'Pejabat Struktural']);
        });
    }
}