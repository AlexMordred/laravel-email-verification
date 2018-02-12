# Laravel Email Verification

[![Packagist](https://img.shields.io/packagist/v/voerro/laravel-email-verification.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-email-verification) [![Packagist](https://img.shields.io/packagist/dt/voerro/laravel-email-verification.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-email-verification) [![Packagist](https://img.shields.io/packagist/l/voerro/laravel-email-verification.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Easily add email verification to your web project or API.

## Installation

1) Install the package using composer:

```bash
composer require voerro/laravel-email-verification
```

2) Laravel has package auto-discovery since version 5.5. If you have an older version, register the service provider in `config/app.php`:

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

4) Add the package's `trait` `EmailVerifiable` to your User model to add helper methods to it, namely the `verificationToken()` relationship and a method to check if the user's email has been verified `emailVerified()`.

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

### Further Installation - Web Project

Further steps for a regular web project. Skip this section if you're developing an API.

1) Override the `registered` method on the `RegisterController` file. Call the package's `registered()` method inside.

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

The `registered()` method generates a token for a newly registered user, emails it to them, logs the user out, and redirects them to the login page with a message. More on this later.

2) Register the package's middleware which logs out and redirect to the login page all authenticated users with unverified emails. You can do this in the `app/Http/Kernel.php` file like this.

```php
...
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    ...
    'web' => [
        ...
        \Voerro\Laravel\EmailVerification\Middleware\LogoutUnverifiedUsers::class,
    ],
    ...
];
```

You can also register this middleware as a route middleware to apply it to selected routes and not the whole `web` middleware group.

### Further Installation - An API

Further steps for an API project. Skip this section if you're developing a regular web project.

1) Call this method after you've registered a user in your API.

```php
return EmailVerification::registeredApi($user);
```

The `registeredApi()` method generates a token for a newly registered user, emails it to them, and returns a JSON response.

2) Register the package's middleware which logs out all the authenticated users with unverified emails and returns a JSON response with a 409 status code. You can do this in the `app/Http/Kernel.php` file like this.

```php
...
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    ...
    'api' => [
        'throttle:60,1',
        'bindings',
        ...
        \Voerro\Laravel\EmailVerification\Middleware\LogoutUnverifiedUsers::class,
        ...
    ],
    ...
];
```

You can also register this middleware as a route middleware to apply it to selected routes and not the whole `api` middleware group.

3) The email verification link sent in the email will point to whatever domain the API is located at, even if you're using a web client on a different domain, or say a mobile client.

The package provides a GET endpoint `/api/auth/email-verification/{token}` in case you want to handle the verification in some different way. It tries to verify the provided in the URL token and returns a JSON response with a `message` and a 200 status (in case the token is correct) or 404 status (if the token does not exists or is expired).

You'd probably want to use your own mailable in this case as well. Read about how to achieve this below.

## Manually Handling or Customizing the Process

If you'd like to manually handle or customize the process, take a look at the `src/EmailVerification.php` class. This is the main file of the package. You can use all the methods from it directly wherever you want to, each method has a DocBlock.

The `registered()` method that was mentioned above also comes from that class.

### The registered() Method

Below is the code of the original method.

```php
public static function registered($user)
{
    $token = self::generateToken($user->id);
    $token->sendVerificationEmail(UserRegistered::class);

    auth()->logout();

    return redirect(config('email_verification.redirect_on_failure'))
        ->with(
        'status',
        __('email-verification::email_verification.message.after.registration')
    );
}
```

And this is the `registeredApi()` method under the hood in case you're developing an API:

```php
public static function registeredApi($user)
{
    $token = self::generateToken($user->id);
    $token->sendVerificationEmail(UserRegistered::class);

    auth()->logout();

    return response()->json([
        'message' => __('email-verification::email_verification.message.after.registration'),
        'status' => 200
    ], 200);
}
```

### Using Custom Mailable

Instead of calling the `registered()` or `registeredApi()` methods in your code, copy the content of a method and paste it into your code wherever you need it. Pass your mailable to the `$token->sendVerificationEmail()` method. Your mailable has to accept a `$token` variable in the constructor. Use this variable to form the verification URL inside your mailable.

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