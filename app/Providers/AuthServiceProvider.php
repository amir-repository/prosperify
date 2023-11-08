<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Donation;
use App\Models\User;
use App\Policies\DonationPolicy;
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
        Donation::class => DonationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is-donor', function (User $user) {
            return $user->type === 'donor';
        });
        Gate::define('is-volunteer', function (User $user) {
            return $user->type === 'volunteer';
        });
        Gate::define('is-admin', function (User $user) {
            return $user->type === 'admin';
        });
    }
}
