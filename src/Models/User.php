<?php

namespace Voerro\Laravel\EmailVerification\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Voerro\Laravel\EmailVerification\Traits\EmailVerifiable;

/**
 * USED ONLY FOR PACKAGE TESTS
*/
class User extends Authenticatable
{
    use EmailVerifiable;

    protected $table = 'users';

    protected $guarded = [];
}
