<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfilePhoneOtpTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::create([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Uji OTP',
            'email' => 'otp@example.com',
            'password' => Hash::make('lama123'),
            'telepon' => '0812777888',
        ]);
    }

    public function test_halaman_profil_memuat_form_verifikasi_telepon(): void
    {
        $response = $this->actingAs($this->makeUser())->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Verifikasi Telepon', false);
        $response->assertSee('Kirim Kode OTP', false);
    }

    public function test_kirim_otp_menyimpan_kode_ke_database(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/profile/phone/send-otp');

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->phone_otp);
        $this->assertNotNull($user->fresh()->phone_otp_expires_at);
    }

    public function test_verifikasi_otp_benar_menandai_terverifikasi(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/profile/phone/send-otp');
        $otp = $user->fresh()->phone_otp;
        $this->assertNotNull($otp);

        $response = $this->actingAs($user)->post('/profile/phone/verify', [
            'phone_otp' => $otp,
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->telepon_verified_at);
    }
}
