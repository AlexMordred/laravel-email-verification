<?php

namespace Voerro\Laravel\EmailVerification\Traits;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;

trait EmailVerifiable
{
    /**
     * Get the verification token Model instance associated with a user
     *
     * @return void
     */
    public function verificationToken()
    {
        return $this->hasOne(EmailVerificationToken::class);
    }

    /**
     * Check if the email of a user is verified
     *
     * @return void
     */
    public function emailVerified()
    {
        if (!$this->verificationToken) {
            return false;
        }

        return $this->verificationToken->verified;
    }
}
