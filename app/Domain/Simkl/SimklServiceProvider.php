<?php

namespace App\Domain\Simkl;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SimklServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SimklAPI::class, function (Application $app) {
            return new SimklAPI(
                config('simkl.id'),
                config('simkl.secret'),
            );
        });
    }
}
