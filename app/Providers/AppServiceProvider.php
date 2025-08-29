<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TravelOrder;
use App\Policies\TravelOrderPolicy;




class AppServiceProvider extends ServiceProvider
{

    public function register(): void {}


    public function boot(): void
    {

        Gate::define('is-admin', function ($user) {
            return $user->is_admin;
        });


        Gate::policy(TravelOrder::class, TravelOrderPolicy::class);
    }
}
