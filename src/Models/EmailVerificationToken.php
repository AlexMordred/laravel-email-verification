<?php

namespace Voerro\Laravel\EmailVerification\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    protected $table = 'voerro_email_verification_tokens';

    protected $guarded = [];

    protected $primaryKey = 'user_id';

    protected $casts = [
        'verified' => 'boolean'
    ];
}
