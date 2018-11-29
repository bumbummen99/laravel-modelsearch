<?php

namespace ModelSearch;

use Illuminate\Support\ServiceProvider;

class ModelSearchServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {}

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/modelsearch.php' => config_path('modelsearch.php'),
        ]);
    }
}