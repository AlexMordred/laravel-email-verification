<?php

namespace Voerro\Laravel\EmailVerification\Controllers;

use Voerro\Laravel\EmailVerification\EmailVerification;

class EmailVerificationController
{
    public function verify($token)
    {
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
    }
}
