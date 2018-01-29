<?php

namespace Voerro\Laravel\EmailVerification\Middleware;

use Closure;
use Voerro\Laravel\EmailVerification\EmailVerification;

class LogoutUnverifiedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !EmailVerification::userVerified(auth()->id())) {
            auth()->logout();

            return redirect(config('email_verification.redirect_on_failure'))
                ->with('status', 'Your email is not verified. Please check your inbox for verification a message from us.');
        }

        return $next($request);
    }
}
