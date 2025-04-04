<?php

namespace App\Providers;

use App\Models\Chall;
use App\Models\Hints;
use App\Observers\ChallObserver;
use App\Observers\HintObserver;
use Illuminate\Support\ServiceProvider;

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
        //
        Chall::observe(ChallObserver::class);
        Hints::observe(HintObserver::class);
    }
}
