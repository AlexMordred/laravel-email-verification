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

        if ($this->app->config->get('email_verification') === null) {
            $this->app->config->set('email_verification', require __DIR__ . '/config/email_verification.php');
        }
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/email_verification.php' => config_path('email_verficiation.php')
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'email.verification');
    }
}
