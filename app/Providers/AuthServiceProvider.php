<?php

namespace App\Providers;

use App\Models\GroupEvent;
use App\Policies\GroupEventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        GroupEvent::class => GroupEventPolicy::class,
        // Add other model => policy mappings here...
        // \App\Models\Group::class => \App\Policies\GroupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Optional: super-admin shortcut (adjust to your app’s admin check)
        // Gate::before(function ($user, $ability) {
        //     return $user->is_admin ? true : null;
        // });
    }
}
