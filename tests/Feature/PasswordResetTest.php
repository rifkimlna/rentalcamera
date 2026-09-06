<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_link_reset_bisa_diminta_dan_pesan_sukses_tampil(): void
    {
        $this->markTestSkipped('Metode link reset via email dinonaktifkan sampai SMTP dikonfigurasi.');
        Notification::fake();

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Uji Reset',
            'email' => 'reset@example.com',
            'password' => Hash::make('lama123'),
            'telepon' => '0812000111',
        ]);

        $response = $this->post('/forgot-password', ['email' => 'reset@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_email_tidak_terdaftar_menolak_dengan_error(): void
    {
        $this->markTestSkipped('Metode link reset via email dinonaktifkan sampai SMTP dikonfigurasi.');
        $response = $this->post('/forgot-password', ['email' => 'tidakada@example.com']);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_bisa_direset_dengan_token_valid(): void
    {
        $this->markTestSkipped('Metode link reset via email dinonaktifkan sampai SMTP dikonfigurasi.');
        Notification::fake();

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Uji Reset Dua',
            'email' => 'reset2@example.com',
            'password' => Hash::make('lama123'),
            'telepon' => '0812000222',
        ]);

        $this->post('/forgot-password', ['email' => 'reset2@example.com']);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($n) use (&$token) {
            $token = $n->token;
            return true;
        });

        $this->assertNotNull($token);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'reset2@example.com',
            'password' => 'baru12345',
            'password_confirmation' => 'baru12345',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('baru12345', $user->fresh()->password));
    }

    public function test_reset_via_nomor_hp_berhasil_tanpa_email(): void
    {
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Uji Reset HP',
            'email' => 'resethp@example.com',
            'password' => Hash::make('lama123'),
            'telepon' => '0812333444',
        ]);

        $verify = $this->post('/forgot-password/phone', [
            'email' => 'resethp@example.com',
            'telepon' => '0812333444',
        ]);

        $verify->assertRedirect();
        $verify->assertSessionHas('success');

        $reset = $this->post('/reset-password/phone', [
            'password' => 'baru54321',
            'password_confirmation' => 'baru54321',
        ]);

        $reset->assertRedirect('/login');
        $reset->assertSessionHas('success');
        $this->assertTrue(Hash::check('baru54321', $user->fresh()->password));
    }

    public function test_reset_via_nomor_hp_menolak_kombinasi_salah(): void
    {
        User::create([
            'uuid' => (string) Str::uuid(),
            'nama' => 'Uji Reset HP Dua',
            'email' => 'resethp2@example.com',
            'password' => Hash::make('lama123'),
            'telepon' => '0812555666',
        ]);

        $response = $this->post('/forgot-password/phone', [
            'email' => 'resethp2@example.com',
            'telepon' => '0899999999',
        ]);

        $response->assertSessionHasErrors('telepon');
    }

    public function test_reset_via_nomor_hp_menolak_sesi_kedaluwarsa(): void
    {
        $response = $this->post('/reset-password/phone', [
            'password' => 'baru54321',
            'password_confirmation' => 'baru54321',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('error');
    }
}



