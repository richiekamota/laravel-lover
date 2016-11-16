<?php

namespace Portal\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Portal\Item'        => 'Portal\Policies\ItemPolicy',
        'Portal\Unit'        => 'Portal\Policies\UnitPolicy',
        'Portal\Model'       => 'Portal\Policies\ModelPolicy',
        'Portal\Location'    => 'Portal\Policies\LocationPolicy',
        'Portal\Contract'    => 'Portal\Policies\ContractPolicy',
        'Portal\UnitType'    => 'Portal\Policies\UnitTypePolicy',
        'Portal\Application' => 'Portal\Policies\ApplicationPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is-admin', function ($user) {
            return $user->role == 'admin';
        });

        Gate::define('is-tenant', function ($user) {
            return $user->role == 'tenant';
        });
    }
}
