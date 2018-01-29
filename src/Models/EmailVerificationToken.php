<?php

namespace Voerro\Laravel\EmailVerification\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Voerro\Laravel\EmailVerification\Mail\UserRegistered;
use Illuminate\Support\Facades\Mail;

class EmailVerificationToken extends Model
{
    protected $table = 'voerro_email_verification_tokens';

    protected $guarded = [];

    protected $primaryKey = 'user_id';

    public $incrementing = false;

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

    /**
     * Send the verification token to the user's email
     *
     * @return void
     */
    public function sendVerificationEmail()
    {
        if (!$user = DB::table('users')->find($this->user_id)) {
            return false;
        }

        Mail::to($user->email)->send(new UserRegistered($this->token));

        return true;
    }
}
