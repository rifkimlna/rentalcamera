<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Methods
    public function isExpired()
    {
        $lifetime = config('session.lifetime') * 60;
        return (time() - $this->last_activity) > $lifetime;
    }

    public function getExpiresAtAttribute()
    {
        $lifetime = config('session.lifetime') * 60;
        return $this->last_activity + $lifetime;
    }

    public function getPayloadDataAttribute()
    {
        return unserialize(base64_decode($this->payload));
    }
}