<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        // Force HTTPS in production
        if ($request->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        // Super Admin & Admin dapat manage users (all permissions)
        Gate::define('manage-users', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin'], true);
        });

        // Super Admin & Admin dapat manage semua data
        Gate::define('manage-all', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin'], true);
        });

        // Guru dapat manage sertifikat dan siswa, tapi tidak manage users
        Gate::define('manage-sertifikat', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin', 'guru'], true);
        });

        Gate::define('manage-siswa', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin', 'guru'], true);
        });

        // Staf, Yayasan, Perusahaan hanya bisa view dashboard
        Gate::define('view-dashboard', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin', 'guru', 'staf', 'yayasan', 'perusahaan'], true);
        });

        // Akses ke management features (users, laporan)
        Gate::define('is-admin', function ($user): bool {
            return in_array($user->role, ['super_admin', 'admin'], true);
        });
    }
}
