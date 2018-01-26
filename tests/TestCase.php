<?php

namespace Voerro\SimplePackage\Test;

use Voerro\SimplePackage\SimplePackageFacade;
use Voerro\SimplePackage\SimplePackageServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return Voerro\SimplePackage\SimplePackageServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [SimplePackageServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'SimplePackage' => SimplePackageFacade::class,
        ];
    }
}
