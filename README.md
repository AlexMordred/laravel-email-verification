# Laravel Email Verification

[![Packagist](https://img.shields.io/packagist/v/voerro/laravel-email-verification.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-email-verification) [![Packagist](https://img.shields.io/packagist/dt/voerro/laravel-email-verification.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-email-verification) [![Packagist](https://img.shields.io/packagist/l/voerro/laravel-email-verification.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Easily add email verification to your project.

## Installation

1) Install the package using composer:

```bash
composer require voerro/laravel-email-notification
```

2) Laravel 5.5 has package auto-discovery. If you have an older version, register the service provider in `config/app.php`:

```php
...
'providers' => [
    ...
    Voerro\Laravel\EmailVerification\EmailVerificationServiceProvider::class,
    ...
],
...
```

3) Run the migration to install the package's table to manage verification tokens:

```bash
php artisan migrate
```

4) Override the `registered` method on the `RegisterController` file. Call the package's `->registered()` method inside.

```php
...
/**
 * The user has been registered.
 *
 * @param  \Illuminate\Http\Request  $request
    * @param  mixed  $user
    * @return mixed
    */
protected function registered($request, $user)
{
    return EmailVerification::registered($user);
}
...
```

The `->registered()` method generates a token for a newly registered user, emails it to them, logs the user out, and redirects them to the login page with a message. More on this later.

5) Register the package's middleware which will logout and redirect to the login page users with unverified emails. You can do this in the `app/Http/Kernel.php` file like this.

```php
...
/**
 * The application's route middleware groups.
 *
 * @var array
    */
protected $middlewareGroups = [
    'web' => [
        ...
        \Voerro\Laravel\EmailVerification\Middleware\LogoutUnverifiedUsers::class,
    ],
    ...
];
```

You can also register this middleware as a route middleware to apply it to selected routes and not the whole `web` middleware group.

6) Optionally add the package's `trait` `EmailVerifiable` to your User model to add helper methods to it, namely the `verificationToken()` relationship and a method to check if the user's email has been verified `emailVerified()`.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Voerro\Laravel\EmailVerification\Traits\EmailVerifiable;

class User extends Authenticatable
{
    use Notifiable, EmailVerifiable;

    ...
}
```

## Manually Handling or Customizing the Process

If you'd like to manually handle or customize the process, take a look at the `src/EmailVerification.php` class. This is the main file of the package. You can use all the methods from it directly whenever you want to, each method has a DocBlock.

The `registered()` method that was mentioned above also comes from that class.

## Using Your Own Mailable

You have to copy the code from the `registered()` method and modify it to use custom mailables. Pass your mailable to the `$token->sendVerificationEmail()` method.

```php
$token = self::generateToken($user->id);
$token->sendVerificationEmail(UserRegistered::class);

auth()->logout();

return redirect(config('email_verification.redirect_on_failure'))
    ->with(
    'status',
    __('email-verification::email_verification.message.after.registration')
);
```

## Configuration and Translations

If you want to edit the configuration file or the translations, run `php artisan vendor:publish` and choose `Voerro\Laravel\EmailVerification\EmailVerificationServiceProvider` from the list.

The configuration file will be copied to `config/email_verficiation.php` while the translation files to the `resources/lang/vendor/email-verification/` folder. By default translations to the following languages are available:
- English
- Russian

## Notifications

The package attaches notifications to the session after certain events have taken place. You can access those in your controllers and views via `session('status)`. For example, you can add this to your main layout file:

```html
@if (session('status'))
    <p class="alert alert-info">{{ session('status') }}</p>
@endif
```

## License

This is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).