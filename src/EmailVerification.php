<?php

namespace Voerro\Laravel\EmailVerification;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;
use Carbon\Carbon;

class EmailVerification
{
    /**
     * Generates an email verification token for a user. If a token for the user
     * exists - generate a new one and update the expiration date.
     *
     * @param integer $userId
     * @return void
     */
    public static function generateToken($userId)
    {
        if (self::userVerified($userId)) {
            return;
        }

        $data = [
            'user_id' => $userId,
            'token' => md5(str_random(25) . Carbon::now()->timestamp),
            'valid_until' => Carbon::now()->addDays(config('email_verification.valid_for'))
        ];

        $record = EmailVerificationToken::find($userId);

        if (!$record) {
            $record = EmailVerificationToken::create($data);
        } else {
            $record->update($data);
        }

        return $record;
    }

    /**
     * Check if a user's email is verified
     *
     * @param integer $userId
     * @return boolean
     */
    public static function userVerified($userId)
    {
        if (!$record = EmailVerificationToken::find($userId)) {
            return false;
        }

        return $record->verified;
    }

    /**
     * Check if a token exists
     *
     * @param string $token
     * @return boolean
     */
    public static function tokenExists($token)
    {
        return EmailVerificationToken::where('token', $token)->exists();
    }
}
