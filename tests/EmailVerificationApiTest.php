<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\Laravel\EmailVerification\EmailVerification;
use Illuminate\Support\Facades\DB;

class EmailVerificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function testAccountVerificationWhenVisitingTheVerificationUrl()
    {
        $this->withoutExceptionHandling();
        $this->get(route('api.auth.email.verification', 'fake-token'))
            ->assertStatus(404)
            ->assertJson([
                'message' => __('email-verification::email_verification.message.token.doesnt.exist'),
                'status' => 404
            ]);

        $userId = DB::table(config('email_verification.users_table'))->insertGetId([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $record = EmailVerification::generateToken($userId)->fresh();

        $this->assertFalse($record->verified);

        $this->get(route('api.auth.email.verification', $record->token))
            ->assertStatus(200)
            ->assertJson([
                'message' => __('email-verification::email_verification.message.verification.success'),
                'status' => 200
            ]);

        $this->assertTrue($record->fresh()->verified);
    }
}
