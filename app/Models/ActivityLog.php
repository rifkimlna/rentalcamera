<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'ip_address',
        'user_agent',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $limit = 100)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function getTypeLabelAttribute()
    {
        $types = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Buat',
            'update' => 'Update',
            'delete' => 'Hapus',
            'view' => 'Lihat',
            'download' => 'Download',
            'upload' => 'Upload',
            'payment' => 'Pembayaran',
            'transaction' => 'Transaksi',
        ];
        return $types[$this->type] ?? $this->type;
    }

    // Static methods for logging activities
    public static function log($userId, $type, $description, $data = null, $ipAddress = null, $userAgent = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'data' => $data,
        ]);
    }

    public static function logLogin($userId, $ipAddress = null, $userAgent = null)
    {
        return self::log($userId, 'login', 'User login ke sistem', null, $ipAddress, $userAgent);
    }

    public static function logLogout($userId, $ipAddress = null, $userAgent = null)
    {
        return self::log($userId, 'logout', 'User logout dari sistem', null, $ipAddress, $userAgent);
    }

    public static function logCreate($userId, $model, $data = null)
    {
        return self::log($userId, 'create', "Membuat {$model}", $data);
    }

    public static function logUpdate($userId, $model, $data = null)
    {
        return self::log($userId, 'update', "Memperbarui {$model}", $data);
    }

    public static function logDelete($userId, $model, $data = null)
    {
        return self::log($userId, 'delete', "Menghapus {$model}", $data);
    }

    public static function logView($userId, $model, $data = null)
    {
        return self::log($userId, 'view', "Melihat {$model}", $data);
    }
}