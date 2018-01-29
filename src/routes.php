<?php

use Voerro\Laravel\EmailVerification\EmailVerification;

Route::middleware('web')->group(function () {
    Route::get('/auth/email-verification/{token}', function ($token) {
        $result = EmailVerification::verify($token);

        if ($result !== true) {
            return redirect(config('email_verification.redirect_on_failure'))
                ->with('status', $result);
        }

        return redirect(config('email_verification.redirect_on_success'))
            ->with(
                'status',
                __('email-verification::email_verification.message.verification.success')
            );
    })->name('auth.email.verification');
});
