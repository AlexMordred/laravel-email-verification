<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Voerro\Laravel\EmailVerification\EmailVerificationServiceProvider;
use Voerro\Laravel\EmailVerification\EmailVerificationFacade;

class TestCase extends OrchestraTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations([]);
    }

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return Voerro\Laravel\EmailVerification\EmailVerificationServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [EmailVerificationServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'EmailVerification' => EmailVerificationFacade::class,
        ];
    }
}
