<?php

namespace Voerro\Laravel\EmailVerification;

use Illuminate\Support\Facades\Facade;

class EmailVerificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'voerro-email-verification';
    }
}
