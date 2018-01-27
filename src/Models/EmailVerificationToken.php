<?php

namespace Voerro\Laravel\EmailVerification\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    protected $table = 'voerro_email_verification_tokens';

    protected $guarded = [];

    protected $casts = [
        'verified' => 'boolean'
    ];
}
