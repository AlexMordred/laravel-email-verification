<?php

namespace Voerro\Laravel\EmailVerification;

use Illuminate\Support\ServiceProvider;

class EmailVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EmailVerification::class, function () {
            return new EmailVerification();
        });

        $this->app->alias(EmailVerification::class, 'laravel-email-verification');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ]);
    }
}
