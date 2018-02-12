<?php

Route::middleware('web')->group(function () {
    Route::get('/auth/email-verification/{token}', 'Voerro\Laravel\EmailVerification\Controllers\EmailVerificationWebController@verify')->name('auth.email.verification');
});

Route::middleware('api')->group(function () {
    Route::get('/api/auth/email-verification/{token}', 'Voerro\Laravel\EmailVerification\Controllers\EmailVerificationApiController@verify')->name('api.auth.email.verification');
});
