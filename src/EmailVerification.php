<?php

namespace Voerro\Laravel\EmailVerification;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;
use Carbon\Carbon;

class EmailVerification
{
    /**
     * Generates an email verification token for a user. If a token for the user
     * exists - generate a new one and update the expiration date.
     *
     * @param App\User $user
     * @return void
     */
    public static function generateToken($userId)
    {
        if (self::userVerified($userId)) {
            return;
        }

        $data = [
            'user_id' => $userId,
            'token' => md5(str_random(25) . Carbon::now()->timestamp),
            'valid_until' => Carbon::now()->addDays(config('email_verification.valid_for'))
        ];

        $record = EmailVerificationToken::find($userId);

        if (!$record) {
            $record = EmailVerificationToken::create($data);
        } else {
            $record->update($data);
        }

        return $record;
    }

    // - проверка активирован ли юзер
    public static function userVerified($userId)
    {
        return false;
    }

    // - отправка email с токеном (+ make a Mailable)
    // - проверка существует ли токен
    // - проверка не истек ли токен
    // - удаление токена
    // - активация аккаунта (находим токен)
    // - удаление юзера (?)
}
