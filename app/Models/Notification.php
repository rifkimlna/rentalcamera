<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeTransaction($query)
    {
        return $query->where('type', 'transaction');
    }

    public function scopePayment($query)
    {
        return $query->where('type', 'payment');
    }

    public function scopeSystem($query)
    {
        return $query->where('type', 'system');
    }

    public function scopePromotion($query)
    {
        return $query->where('type', 'promotion');
    }

    // Methods
    public function getTypeLabelAttribute()
    {
        $types = [
            'transaction' => 'Transaksi',
            'payment' => 'Pembayaran',
            'system' => 'Sistem',
            'promotion' => 'Promosi',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        return $this->save();
    }

    public function markAsUnread()
    {
        $this->is_read = false;
        $this->read_at = null;
        return $this->save();
    }

    // Static methods for creating notifications
    public static function sendTransactionNotification($userId, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'transaction',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function sendPaymentNotification($userId, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'payment',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function sendSystemNotification($userId, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function sendPromotionNotification($userId, $title, $message, $data = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'promotion',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function sendToAdmins($type, $title, $message, $data = null)
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get(['id']);
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        }
    }
}