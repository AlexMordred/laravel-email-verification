<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Voerro\Laravel\EmailVerification\Models\EmailVerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\Laravel\EmailVerification\EmailVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Voerro\Laravel\EmailVerification\Mail\UserRegistered;

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

    public function testUserVerifiedMethod()
    {
        $this->assertFalse(EmailVerification::userVerified(1));

        $record = EmailVerification::generateToken(1);

        $this->assertFalse(EmailVerification::userVerified(1));

        $record->update(['verified' => true]);

        $this->assertTrue(EmailVerification::userVerified(1));
    }

    public function testFindTokenMethod()
    {
        $this->assertNull(EmailVerification::findToken('fake-token'));

        $record = EmailVerification::generateToken(1);

        $this->assertNotNull(EmailVerification::findToken($record->token));
    }

    public function testTokenExistsMethod()
    {
        $this->assertFalse(EmailVerification::tokenExists('fake-token'));

        $record = EmailVerification::generateToken(1);

        $this->assertTrue(EmailVerification::tokenExists($record->token));
    }

    // public function testTokenValidMethod()
    // {
    //     $record = EmailVerification::generateToken(1);

    //     $this->assertTrue(EmailVerification::tokenValid($record->token));

    //     $expiredRecord = EmailVerificationToken::create([
    //         'user_id' => 2,
    //         'token' => 'expired-token',
    //         'valid_until' => Carbon::now()->subDays(1)
    //     ]);

    //     $this->assertFalse(EmailVerification::tokenValid($expiredRecord->token));
    // }

    public function testTokenValidityCheckMethod()
    {
        $record = EmailVerification::generateToken(1);

        $this->assertTrue($record->isValid());

        $expiredRecord = EmailVerificationToken::create([
            'user_id' => 2,
            'token' => 'expired-token',
            'valid_until' => Carbon::now()->subDays(1)
        ]);

        $this->assertFalse($expiredRecord->isValid());
    }

    public function testVerifyingUnexistingToken()
    {
        $result = EmailVerification::verify('fake-token');

        $this->assertEquals(
            __('email-verification::email_verification.message.token.doesnt.exist'),
            $result
        );
    }

    public function testVerifyingExpiredToken()
    {
        $expiredRecord = EmailVerificationToken::create([
            'user_id' => 2,
            'token' => 'expired-token',
            'valid_until' => Carbon::now()->subDays(1)
        ]);

        $result = EmailVerification::verify($expiredRecord->token);

        $this->assertEquals(
            __('email-verification::email_verification.message.token.has.expired'),
            $result
        );
    }

    public function testVerifyingTokenThatIsAlreadyVerified()
    {
        $record = EmailVerification::generateToken(1);

        $result = EmailVerification::verify($record->token);

        $this->assertTrue($result);
        $this->assertTrue($record->fresh()->verified);

        $result = EmailVerification::verify($record->token);

        $this->assertEquals(
            __('email-verification::email_verification.message.token.doesnt.exist'),
            $result
        );
    }

    public function testVerifyingToken()
    {
        $record = EmailVerification::generateToken(1);

        $result = EmailVerification::verify($record->token);

        $this->assertTrue($result);
        $this->assertTrue($record->fresh()->verified);
    }

    public function testSendingVerificationEmailMessageToUnexistingUser()
    {
        Mail::fake();

        $record = EmailVerification::generateToken(1);

        $result = $record->sendVerificationEmail();

        $this->assertFalse($result);

        Mail::assertNotSent(UserRegistered::class);
    }

    public function testSendingVerificationEmailMessage()
    {
        Mail::fake();

        $userId = DB::table(config('email_verification.users_table'))->insertGetId([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $record = EmailVerification::generateToken($userId);

        $result = $record->sendVerificationEmail();

        Mail::assertSent(UserRegistered::class);

        $this->assertTrue($result);
    }

    public function testAccountVerificationWhenVisitingTheVerificationUrl()
    {
        $this->withoutExceptionHandling();
        $this->get(route('auth.email.verification', 'fake-token'))
            ->assertStatus(302)
            ->assertRedirect(config('email_verification.redirect_on_failure'));

        $userId = DB::table(config('email_verification.users_table'))->insertGetId([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $record = EmailVerification::generateToken($userId)->fresh();

        $this->assertFalse($record->verified);

        $this->get(route('auth.email.verification', $record->token))
            ->assertStatus(302)
            ->assertRedirect(config('email_verification.redirect_on_success'));

        $this->assertTrue($record->fresh()->verified);
    }
}
