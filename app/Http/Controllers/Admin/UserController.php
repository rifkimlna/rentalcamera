<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaksis; // GANTI: dari Transaksi ke Transaksis
use App\Models\DepositTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } else {
            $query->where('role', 'customer'); // Default filter customers
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $roles = [
            'customer' => 'Customer',
            'admin' => 'Admin',
            'superadmin' => 'Super Admin',
            'driver' => 'Driver',
        ];
        
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
            'suspended' => 'Ditangguhkan',
            'pending_verification' => 'Menunggu Verifikasi',
        ];
        
        return view('admin.users.index', compact('users', 'roles', 'statuses'));
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        // Gunakan relationship yang sesuai dengan Model User
        $user = User::with([
            'transaksis' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }, 
            'depositTransactions' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            },
            'ulasan' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = [
            'customer' => 'Customer',
            'admin' => 'Admin',
            'driver' => 'Driver',
        ];
        
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
            'suspended' => 'Ditangguhkan',
            'pending_verification' => 'Menunggu Verifikasi',
        ];
        
        return view('admin.users.create', compact('roles', 'statuses'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telepon' => 'required|string|max:20',
            'role' => 'required|in:customer,admin,driver',
            'status' => 'required|in:active,inactive,suspended,pending_verification',
            'saldo_deposit' => 'nullable|numeric|min:0',
            'poin_reward' => 'nullable|integer|min:0',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $userData = $request->only([
            'nama', 'email', 'telepon', 'role', 'status',
            'alamat', 'kota', 'provinsi', 'kode_pos',
            'tanggal_lahir', 'jenis_kelamin'
        ]);
        
        $userData['password'] = Hash::make($request->password);
        $userData['saldo_deposit'] = $request->saldo_deposit ?? 0;
        $userData['saldo_credit'] = 0;
        $userData['poin_reward'] = $request->poin_reward ?? 0;
        $userData['email_verified_at'] = $request->status === 'active' ? now() : null;
        $userData['uuid'] = Str::uuid();
        
        $user = User::create($userData);
        
        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            $this->uploadUserImage($user, $request->file('foto_profil'), 'foto_profil');
        }
        
        // Handle KTP upload
        if ($request->hasFile('ktp_image')) {
            $this->uploadUserImage($user, $request->file('ktp_image'), 'ktp_image');
        }
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'user',
            'description' => "Menambahkan user baru: {$user->nama} ({$user->email})",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $roles = [
            'customer' => 'Customer',
            'admin' => 'Admin',
            'driver' => 'Driver',
        ];
        
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
            'suspended' => 'Ditangguhkan',
            'pending_verification' => 'Menunggu Verifikasi',
        ];
        
        return view('admin.users.edit', compact('user', 'roles', 'statuses'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'required|string|max:20',
            'role' => 'required|in:customer,admin,driver',
            'status' => 'required|in:active,inactive,suspended,pending_verification',
            'saldo_deposit' => 'nullable|numeric|min:0',
            'poin_reward' => 'nullable|integer|min:0',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        $userData = $request->only([
            'nama', 'email', 'telepon', 'role', 'status',
            'alamat', 'kota', 'provinsi', 'kode_pos',
            'tanggal_lahir', 'jenis_kelamin'
        ]);
        
        $userData['saldo_deposit'] = $request->saldo_deposit ?? $user->saldo_deposit;
        $userData['poin_reward'] = $request->poin_reward ?? $user->poin_reward;
        
        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        // Update email verification status
        if ($request->status === 'active' && !$user->email_verified_at) {
            $userData['email_verified_at'] = now();
        } elseif ($request->status !== 'active') {
            $userData['email_verified_at'] = null;
        }
        
        $user->update($userData);
        
        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            $this->uploadUserImage($user, $request->file('foto_profil'), 'foto_profil');
        }
        
        // Handle KTP upload
        if ($request->hasFile('ktp_image')) {
            $this->uploadUserImage($user, $request->file('ktp_image'), 'ktp_image');
            // Reset KTP verification when new KTP uploaded
            $user->update(['ktp_verified_at' => null]);
        }
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'user',
            'description' => "Memperbarui data user: {$user->nama}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deletion of superadmin
        if ($user->role === 'superadmin') {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus super admin.');
        }
        
        // Check if user has transactions
        if ($user->transaksis()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus user yang memiliki transaksi.');
        }
        
        // Delete user images
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }
        
        if ($user->ktp_image && Storage::disk('public')->exists($user->ktp_image)) {
            Storage::disk('public')->delete($user->ktp_image);
        }
        
        // Log activity before deletion
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'user',
            'description' => "Menghapus user: {$user->nama} ({$user->email})",
            'ip_address' => request()->ip(),
        ]);
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,pending_verification'
        ]);
        
        $oldStatus = $user->status;
        $newStatus = $request->status;
        
        $updateData = ['status' => $newStatus];
        
        // Update email verification based on status
        if ($newStatus === 'active' && !$user->email_verified_at) {
            $updateData['email_verified_at'] = now();
        } elseif ($newStatus !== 'active') {
            $updateData['email_verified_at'] = null;
        }
        
        $user->update($updateData);
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'user',
            'description' => "Mengubah status user {$user->nama} dari {$oldStatus} menjadi {$newStatus}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Status user berhasil diperbarui.');
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Prevent non-superadmin from creating superadmin
        if ($request->role === 'superadmin' && $currentUser && $currentUser->role !== 'superadmin') {
            return redirect()->back()
                ->with('error', 'Hanya super admin yang dapat membuat super admin.');
        }
        
        $request->validate([
            'role' => 'required|in:customer,admin,superadmin,driver'
        ]);
        
        $oldRole = $user->role;
        $newRole = $request->role;
        
        $user->update(['role' => $newRole]);
        
        // Log activity
        ActivityLog::create([
            'user_id' => $currentUser ? $currentUser->id : null,
            'type' => 'user',
            'description' => "Mengubah role user {$user->nama} dari {$oldRole} menjadi {$newRole}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Role user berhasil diperbarui.');
    }

    /**
     * Verify user KTP.
     */
    public function verifyKTP(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'verified' => 'required|boolean'
        ]);
        
        if ($request->verified) {
            if (!$user->ktp_image) {
                return redirect()->back()
                    ->with('error', 'User belum mengupload KTP.');
            }
            
            $user->update(['ktp_verified_at' => now()]);
            $message = 'KTP berhasil diverifikasi.';
        } else {
            $user->update(['ktp_verified_at' => null]);
            $message = 'Verifikasi KTP dibatalkan.';
        }
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'user',
            'description' => "{$message} untuk user {$user->nama}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Update user deposit.
     */
    public function updateDeposit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'type' => 'required|in:topup,withdraw,penalty,reward',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);
        
        $previousBalance = $user->saldo_deposit;
        
        switch ($request->type) {
            case 'topup':
            case 'reward':
                $user->increment('saldo_deposit', $request->amount);
                break;
            case 'withdraw':
            case 'penalty':
                if ($user->saldo_deposit < $request->amount) {
                    return redirect()->back()
                        ->with('error', 'Saldo deposit tidak mencukupi.');
                }
                $user->decrement('saldo_deposit', $request->amount);
                break;
        }
        
        $currentBalance = $user->refresh()->saldo_deposit;
        
        // Create deposit transaction record
        DepositTransaction::create([
            'user_id' => $user->id,
            'kode_transaksi' => 'ADM' . date('YmdHis') . rand(1000, 9999),
            'type' => $request->type,
            'amount' => $request->amount,
            'previous_balance' => $previousBalance,
            'current_balance' => $currentBalance,
            'status' => 'success',
            'description' => $request->description . ' (Admin)',
        ]);
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'deposit',
            'description' => "{$request->type} deposit untuk user {$user->nama}: Rp " . number_format($request->amount, 0, ',', '.'),
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Deposit berhasil diperbarui.');
    }

    /**
     * Update user reward points.
     */
    public function updatePoints(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'type' => 'required|in:add,subtract',
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);
        
        if ($request->type === 'add') {
            $user->increment('poin_reward', $request->points);
            $message = 'Poin reward berhasil ditambahkan.';
        } else {
            if ($user->poin_reward < $request->points) {
                return redirect()->back()
                    ->with('error', 'Poin reward tidak mencukupi.');
            }
            $user->decrement('poin_reward', $request->points);
            $message = 'Poin reward berhasil dikurangi.';
        }
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'points_update',
            'description' => "Poin reward user {$user->nama} diubah: {$request->reason} ({$request->points} poin)",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Upload user image (foto_profil or ktp_image).
     */
    private function uploadUserImage(User $user, $file, string $field)
    {
        // Delete old image if exists
        if ($user->$field && Storage::disk('public')->exists($user->$field)) {
            Storage::disk('public')->delete($user->$field);
        }
        
        // Upload new image
        $filename = $field . '-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('users/' . $field, $filename, 'public');
        
        $user->update([$field => $path]);
    }
}