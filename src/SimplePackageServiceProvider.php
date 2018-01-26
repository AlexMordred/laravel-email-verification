<?php

namespace Voerro\SimplePackage;

use Illuminate\Support\ServiceProvider;

class SimplePackageServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SimplePackage::class, function () {
            return new SimplePackage();
        });

        $this->app->alias(SimplePackage::class, 'simple-package');
    }
}
