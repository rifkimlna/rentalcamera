<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            /** @var User $user */
            $user = Auth::user();
            
            // Update last login
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save(); // Menggunakan save() method
            
            // Redirect berdasarkan role
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/');
        }
        
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
        $userData['saldo_deposit'] = 0;
        $userData['saldo_credit'] = 0;
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
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Implementation for password reset
        // Laravel has built-in functionality for this
        
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Display reset password form.
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
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
        
        // Implementation for password reset
        // Laravel has built-in functionality for this
        
        return redirect()->route('login')->with('status', 'Password berhasil direset.');
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
     * Upload KTP.
     */
    public function uploadKTP(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'ktp_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Delete old KTP if exists
        if ($user->ktp_image && Storage::disk('public')->exists($user->ktp_image)) {
            Storage::disk('public')->delete($user->ktp_image);
        }
        
        // Upload new KTP
        $filename = 'ktp-' . $user->id . '-' . time() . '.' . $request->file('ktp_image')->getClientOriginalExtension();
        $path = $request->file('ktp_image')->storeAs('users/ktp', $filename, 'public');
        
        $user->ktp_image = $path;
        $user->ktp_verified_at = null; // Reset verification
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'KTP berhasil diupload. Menunggu verifikasi admin.');
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