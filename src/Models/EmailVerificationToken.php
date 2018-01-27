<?php

namespace Voerro\Laravel\EmailVerification\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerificationToken extends Model
{
    protected $table = 'voerro_email_verification_tokens';

    protected $guarded = [];

    protected $primaryKey = 'user_id';

    protected $casts = [
        'verified' => 'boolean'
    ];

    /**
     * Check if a token is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid_until >= Carbon::now();
    }

    /**
     * Mark the account/email as verified
     *
     * @return boolean
     */
    public function verify()
    {
        $this->update(['verified' => true]);
    }
}
