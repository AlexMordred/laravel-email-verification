<?php

namespace Voerro\Laravel\EmailVerification\Controllers;

use Voerro\Laravel\EmailVerification\EmailVerification;

class EmailVerificationApiController
{
    public function verify($token)
    {
        $result = EmailVerification::verify($token);

        if ($result !== true) {
            return response()->json([
                'message' => $result,
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => __('email-verification::email_verification.message.verification.success'),
            'status' => 200
        ], 200);
    }
}
