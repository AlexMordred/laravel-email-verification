<?php

Route::middleware('web')->group(function () {
    Route::get('/auth/email-verification/{token}', 'Voerro\Laravel\EmailVerification\Controllers\EmailVerificationController@verify')->name('auth.email.verification');
});
