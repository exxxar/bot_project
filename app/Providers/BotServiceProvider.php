<?php

namespace App\Providers;

use App\Clasess\BaseBot;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class BotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        App::singleton(BaseBot::class, function() {
            return new BaseBot();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
