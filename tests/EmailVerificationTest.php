<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\Laravel\EmailVerification\EmailVerification;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function testTokenGeneration()
    {
        $this->assertEmpty(EmailVerificationToken::all());

        EmailVerification::generateToken(1);

        $this->assertCount(1, EmailVerificationToken::all());

        $record = EmailVerificationToken::first();

        $this->assertNotNull($record);
        $this->assertEquals(1, strlen($record->user_id));
        $this->assertEquals(32, strlen($record->token));
        $this->assertFalse($record->verified);
    }

    public function testTokenRegeneration()
    {
        $oldRecord = EmailVerification::generateToken(1);
        $newRecord = EmailVerification::generateToken(1);

        $this->assertCount(1, EmailVerificationToken::all());

        $this->assertNotEquals($oldRecord->token, $newRecord->token);
        $this->assertNotEquals($oldRecord->valid_until, $newRecord->valid_until);
    }
}
