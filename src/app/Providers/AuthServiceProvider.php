<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param Gate $gate
     * @return void
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        $gate->define('update-info', function ($user) {
            foreach ($user->roles as $role) {
                if ($role->permissions->pluck('name')->contains('update-info')) {
                    return true;
                }
            }
            return false;
        });

        $gate->define('update-role', function ($user) {
            foreach ($user->roles as $role) {
                if ($role->permissions->pluck('name')->contains('update-role')) {
                    return true;
                }
            }
            return false;
        });

        $gate->define('update-student-info', function ($user) {
            foreach ($user->roles as $role) {
                if ($role->permissions->pluck('name')->contains('update-student-info')) {
                    return true;
                }
            }
            return false;
        });
    }
}
