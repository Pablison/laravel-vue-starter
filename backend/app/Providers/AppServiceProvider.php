<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
    public function boot(): void
    {
        // Esta linha define a 'manage-users'.
        // Ela retorna 'true' se o usuário logado tiver a role 'admin'.
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
