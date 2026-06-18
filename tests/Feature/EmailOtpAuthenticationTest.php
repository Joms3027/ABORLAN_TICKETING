<?php

namespace Tests\Feature;

use App\Mail\OtpVerification;
use App\Models\AuditLog;
use App\Models\EmailOtp;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailOtpAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_page_renders_successfully(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'password' => Hash::make('SecurePass1'),
        ]);

        $service = app(EmailOtpService::class);
        $result = $service->generateAndSend($user->email, EmailOtp::PURPOSE_LOGIN, '127.0.0.1');

        $this->withSession([
            'otp_pending' => [
                'email' => $user->email,
                'purpose' => EmailOtp::PURPOSE_LOGIN,
                'user_id' => $user->id,
                'remember' => false,
                'session_token' => $result['otp']->session_token,
            ],
        ])->get('/otp/verify')
            ->assertOk()
            ->assertSee('Enter your verification code')
            ->assertSee('Resend code');
    }

    public function test_login_sends_otp_and_redirects_to_verification_page(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'password' => Hash::make('SecurePass1'),
            'is_admin' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'SecurePass1',
        ]);

        $response->assertRedirect(route('otp.verify.show'));
        $this->assertGuest();
        Mail::assertSent(OtpVerification::class, fn ($mail) => $mail->hasTo($user->email));
        $this->assertNotNull(session('otp_pending.session_token'));
    }

    public function test_admin_login_skips_otp_and_grants_access_immediately(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'password' => Hash::make('SecurePass1'),
            'is_admin' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'SecurePass1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->assertNull(session('otp_pending'));
        Mail::assertNothingSent();

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'admin_login',
            'category' => AuditLog::CATEGORY_AUTH,
            'user_id' => $admin->id,
            'email' => strtolower($admin->email),
        ]);

        $audit = AuditLog::query()->where('event', 'admin_login')->first();
        $this->assertTrue($audit->metadata['otp_bypassed'] ?? false);
    }

    public function test_correct_otp_completes_login(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'password' => Hash::make('SecurePass1'),
        ]);

        $service = app(EmailOtpService::class);
        $result = $service->generateAndSend($user->email, EmailOtp::PURPOSE_LOGIN, '127.0.0.1');

        $this->withSession([
            'otp_pending' => [
                'email' => $user->email,
                'purpose' => EmailOtp::PURPOSE_LOGIN,
                'user_id' => $user->id,
                'remember' => false,
                'session_token' => $result['otp']->session_token,
            ],
        ])->post('/otp/verify', [
            'otp_code' => $result['plain_code'],
        ])->assertRedirect(route('bookings.index'));

        $this->assertAuthenticatedAs($user);
        $this->assertTrue($result['otp']->fresh()->is_verified);
    }

    public function test_incorrect_otp_increments_failed_attempts(): void
    {
        $user = User::factory()->create();

        $service = app(EmailOtpService::class);
        $result = $service->generateAndSend($user->email, EmailOtp::PURPOSE_LOGIN, '127.0.0.1');

        $session = [
            'otp_pending' => [
                'email' => $user->email,
                'purpose' => EmailOtp::PURPOSE_LOGIN,
                'user_id' => $user->id,
                'remember' => false,
                'session_token' => $result['otp']->session_token,
            ],
        ];

        $this->withSession($session)->post('/otp/verify', [
            'otp_code' => '000000',
        ])->assertSessionHasErrors('otp_code');

        $this->withSession($session)->get('/otp/verify')->assertOk();

        $this->assertSame(1, $result['otp']->fresh()->failed_attempts);
        $this->assertGuest();
    }

    public function test_resend_enforces_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $service = app(EmailOtpService::class);
        $result = $service->generateAndSend($user->email, EmailOtp::PURPOSE_LOGIN, '127.0.0.1');

        $this->withSession([
            'otp_pending' => [
                'email' => $user->email,
                'purpose' => EmailOtp::PURPOSE_LOGIN,
                'user_id' => $user->id,
                'remember' => false,
                'session_token' => $result['otp']->session_token,
            ],
        ])->post('/otp/resend')
            ->assertSessionHasErrors('otp_code');

        Mail::assertSentCount(1);
    }

    public function test_api_send_returns_json_for_registered_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->postJson('/api/otp/send', [
            'email' => $user->email,
            'purpose' => 'login',
        ])->assertOk()
            ->assertJson(['success' => true]);

        Mail::assertSent(OtpVerification::class);
    }
}
