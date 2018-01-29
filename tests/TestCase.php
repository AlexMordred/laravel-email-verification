<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Voerro\Laravel\EmailVerification\EmailVerificationServiceProvider;

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
}
