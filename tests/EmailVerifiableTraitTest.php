<?php

namespace Voerro\Laravel\EmailVerification\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\Laravel\EmailVerification\EmailVerification;
use Voerro\Laravel\EmailVerification\Models\User;

class EmailVerifiableTraitTest extends TestCase
{
    use RefreshDatabase;

    public function testVerificationTokenRelationship()
    {
        $user = User::create([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $this->assertNull($user->verificationToken);

        $token = EmailVerification::generateToken($user->id);

        $this->assertNotNull($user->fresh()->verificationToken);
    }

    public function testCheckingIfUsersEmailIsVerified()
    {
        $user = User::create([
            'name' => 'test_user',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $token = EmailVerification::generateToken($user->id);

        $this->assertFalse($user->emailVerified());

        $token->verify();

        $this->assertTrue($user->fresh()->emailVerified());
    }
}
