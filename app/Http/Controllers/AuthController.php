<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\PhoneOtpMail;

class AuthController extends Controller
{
    /**
     * Display login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rate limit: maksimal 5 percobaan gagal per menit per kombinasi email + IP
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            /** @var User $user */
            $user = Auth::user();
            
            // Update last login
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save(); // Menggunakan save() method
            
            // Redirect berdasarkan role, hormati halaman tujuan sebelum login (intended)
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            
            return redirect()->intended(route('customer.dashboard'));
        }
        
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Display registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telepon' => 'required|string|max:20|unique:users,telepon',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agree_terms' => 'required|accepted',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $userData = $validator->validated();
        $userData['password'] = Hash::make($userData['password']);
        $userData['uuid'] = Str::uuid();
        $userData['role'] = 'customer';
        $userData['status'] = 'pending_verification';
        $userData['poin_reward'] = 0;
        
        // Hapus field yang tidak ada di database
        unset($userData['agree_terms']);
        unset($userData['password_confirmation']);
        
        /** @var User $user */
        $user = User::create($userData);
        
        // Auto login setelah registrasi
        Auth::login($user);
        
        // Send email verification
        // $user->sendEmailVerificationNotification();
        
        return redirect()->route('customer.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Display forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek kotak masuk / spam.')
            : back()->withErrors(['email' => 'Email tidak terdaftar pada sistem kami.']);
    }

    /**
     * Verifikasi identitas via email + nomor HP untuk reset tanpa email.
     * Dipakai karena pengiriman email (SMTP) belum dikonfigurasi.
     */
    public function verifyResetIdentity(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
        ]);

        $user = User::where('email', $request->email)
            ->where('telepon', $request->telepon)
            ->first();

        if (!$user) {
            return back()
                ->withErrors(['telepon' => 'Kombinasi email dan nomor HP tidak cocok dengan data kami.'])
                ->withInput();
        }

        session([
            'pwd_reset_user_id' => $user->id,
            'pwd_reset_expires' => now()->addMinutes(10)->timestamp,
        ]);

        return back()->with('success', 'Identitas terverifikasi. Silakan buat password baru (berlaku 10 menit).');
    }

    /**
     * Simpan password baru setelah identitas terverifikasi via nomor HP.
     */
    public function resetPasswordByPhone(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $userId = session('pwd_reset_user_id');
        $expires = session('pwd_reset_expires');

        if (!$userId || !$expires || now()->timestamp > $expires) {
            session()->forget(['pwd_reset_user_id', 'pwd_reset_expires']);
            return redirect()->route('password.request')
                ->with('error', 'Sesi verifikasi kedaluwarsa. Silakan ulangi dari awal.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget(['pwd_reset_user_id', 'pwd_reset_expires']);
            return redirect()->route('password.request')
                ->with('error', 'Akun tidak ditemukan. Silakan ulangi dari awal.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['pwd_reset_user_id', 'pwd_reset_expires']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan masuk dengan password baru.');
    }

    /**
     * Display reset password form.
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->query('email'),
        ]);
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan masuk dengan password baru.')
            : back()->withErrors(['email' => 'Token reset password tidak valid atau telah kedaluwarsa.']);
    }

    /**
     * Display email verification notice.
     */
    public function showVerificationNotice()
    {
        $user = Auth::user();

        if ($user && $user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * Verify email from signed URL.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Email Anda berhasil diverifikasi!');
    }

    /**
     * Resend email verification notification.
     */
    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Display profile page.
     */
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();
        
        return view('auth.profile', compact('user'));
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'required|string|max:20|unique:users,telepon,' . $user->id,
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $userData = $validator->validated();
        
        // Update password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini salah.')
                    ->withInput();
            }
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }
        
        // Remove unnecessary fields
        unset($userData['current_password']);
        unset($userData['password_confirmation']);
        
        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            // Delete old image if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            // Upload new image
            $filename = 'foto_profil-' . $user->id . '-' . time() . '.' . $request->file('foto_profil')->getClientOriginalExtension();
            $path = $request->file('foto_profil')->storeAs('users/foto_profil', $filename, 'public');
            $userData['foto_profil'] = $path;
        }
        
        // Reset verifikasi telepon jika nomor berubah
        if (isset($userData['telepon']) && $userData['telepon'] !== $user->telepon) {
            $user->telepon_verified_at = null;
            $user->phone_otp = null;
            $user->phone_otp_expires_at = null;
        }
        
        // Gunakan eloquent update method
        $user->fill($userData);
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'password' => 'required|min:6|confirmed',
        ];

        // Jika user tidak punya auth_provider (login manual), wajib isi current_password
        if (!$user->auth_provider) {
            $rules['current_password'] = 'required';
        } else {
            $rules['current_password'] = 'nullable';
        }

        $request->validate($rules);

        // Cek current_password hanya jika user punya password lama (bukan social login)
        if (!$user->auth_provider) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini salah.');
            }
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Kirim kode OTP verifikasi nomor telepon ke email pengguna.
     */
    public function sendPhoneOtp(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Cegah spam: maksimal 1x per 60 detik
        $throttleKey = 'phone_otp_' . $user->id;
        if (Cache::has($throttleKey)) {
            return back()->with('warning', 'Kode OTP sudah dikirim. Tunggu 60 detik atau gunakan kode terakhir.');
        }

        $otp = (string) random_int(100000, 999999);

        $user->phone_otp = $otp;
        $user->phone_otp_expires_at = now()->addMinutes(5);
        $user->save();

        Cache::put($throttleKey, true, 60);

        Mail::to($user->email)->send(new PhoneOtpMail($user, $otp));

        return back()->with('success', 'Kode verifikasi telah dikirim ke email ' . $user->email . '. Berlaku 5 menit.');
    }

    /**
     * Verifikasi kode OTP yang dimasukkan pengguna.
     */
    public function verifyPhoneOtp(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'phone_otp' => 'required|numeric|digits:6',
        ]);

        $submitted = $request->phone_otp;

        if (!$user->phone_otp || $user->phone_otp !== $submitted) {
            return back()->with('error', 'Kode verifikasi salah.');
        }

        if (!$user->phone_otp_expires_at || now()->greaterThan($user->phone_otp_expires_at)) {
            return back()->with('error', 'Kode verifikasi telah kedaluwarsa. Silakan kirim ulang.');
        }

        $user->phone_otp = null;
        $user->phone_otp_expires_at = null;
        $user->telepon_verified_at = now();
        $user->save();

        Cache::forget('phone_otp_' . $user->id);

        return redirect()->route('profile')
            ->with('success', 'Nomor telepon berhasil diverifikasi.');
    }

    /**
     * Display notifications page.
     */
    public function notifications()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markNotificationsAsRead()
    {
        /** @var User $user */
        $user = Auth::user();
        
        \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return redirect()->back()
            ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Mark notification as read (single).
     */
    public function markNotificationAsRead($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notification = \App\Models\Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
            
        if ($notification) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        
        return redirect()->back();
    }
}