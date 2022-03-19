<?php

namespace HP\CrudGenrator;

use Illuminate\Support\ServiceProvider;

class CrudGenratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->loadViewsFrom(__DIR__.'/views','crud');
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
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
