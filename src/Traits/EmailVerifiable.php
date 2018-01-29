<?php

namespace Voerro\Laravel\EmailVerification\Traits;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;

trait EmailVerifiable
{
    public function verificationToken()
    {
        return $this->hasOne(EmailVerificationToken::class);
    }

    public function emailVerified()
    {
        if (!$this->verificationToken) {
            return false;
        }

        return $this->verificationToken->verified;
    }
}
