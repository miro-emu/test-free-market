<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class MailTest extends TestCase
{
    use RefreshDatabase;

    // メール認証
    public function test_verification_email_is_sent_after_register()
    {
        Notification::fake();
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'mailtest@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->dump();

        $user = \App\Models\User::first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verification_notice_page_is_displayed()
    {
        $user = \App\Models\User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(200);

        $response->assertSee('メール認証を完了してください');
        $response->assertSee('http://localhost:8025/#');
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $user = \App\Models\User::factory()->unverified()->create();

        $verificationUrl = \URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);

        $response->assertRedirect('/mypage/profile');
    }

}
