<?php

namespace Laravel\BotCashBack;

use Illuminate\Support\ServiceProvider;

class BotCashBackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->loadMigrationsFrom("/migrations");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
