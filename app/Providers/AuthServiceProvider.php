<?php

namespace App\Providers;

use App\Policies\DocumentPolicy;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        
        // Gate lama untuk admin
        Gate::define('isAdmin', function (User $user) {
            if (!$user->role) { return false; }
            return in_array($user->role->name, ['Super Admin', 'Admin Arsip']);
        });

        Gate::define('update-document', function (User $user, Document $document) {
            // Izinkan jika user adalah admin ATAU jika user_id di dokumen sama dengan id user yang login
            return Gate::forUser($user)->allows('isAdmin') || $user->id === $document->uploaded_by;

        });

        Gate::define('delete-document', function (User $user, Document $document) {
            // Aturan yang sama dengan update
            return Gate::forUser($user)->allows('isAdmin') || $user->id === $document->uploaded_by;

        });

        Gate::define('isClient', function (User $user) {
            if (!$user->role) { return false; }
            return strtolower(trim($user->role->name)) === 'klien eksternal';
        });

        Gate::define('isInternalUser', function (User $user) {
            // Jika pengguna tidak punya role, kita anggap dia internal (misal: user pertama)
            if (!$user->role) { return true; }
            // Cek langsung berdasarkan nama role, bukan memanggil gate lain
            return strtolower(trim($user->role->name)) !== 'klien eksternal';
        });

        Gate::define('can-disposition', function (User $user) {
            if (!$user->role) {
                return false;
            }
            // Izinkan jika rolenya adalah salah satu dari ini
            return in_array($user->role->name, [
                'Super Admin',
                'Admin Arsip',
                'Pejabat Struktural',
                'Auditor'
            ]);
        });
    }
}