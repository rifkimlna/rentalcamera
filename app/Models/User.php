<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;


// Import models
use App\Models\Transaksis;
use App\Models\Keranjang;
use App\Models\Ulasan;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\VoucherUsage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'google_id',
        'auth_provider',
        'auth_provider_id',
        'nama',
        'email',
        'password',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'tanggal_lahir',
        'jenis_kelamin',
        'foto_profil',
        'role',
        'status',

        'poin_reward',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'telepon_verified_at' => 'datetime',
        'phone_otp_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'tanggal_lahir' => 'date',

    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksis::class, 'user_id');
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'user_id');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function voucherUsages()
    {
        return $this->hasMany(VoucherUsage::class, 'user_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is customer
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Get the user's full name (since we don't have separate first/last name)
     */
    public function getFullNameAttribute()
    {
        return $this->nama;
    }
}