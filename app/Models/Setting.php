<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Scopes
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeGeneral($query)
    {
        return $query->where('group', 'general');
    }

    public function scopePayment($query)
    {
        return $query->where('group', 'payment');
    }

    public function scopeShipping($query)
    {
        return $query->where('group', 'shipping');
    }

    public function scopeRental($query)
    {
        return $query->where('group', 'rental');
    }

    public function scopeEmail($query)
    {
        return $query->where('group', 'email');
    }

    // Methods
    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? $value + 0 : 0;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return explode(',', $value);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->attributes['value'] = $value ? 'true' : 'false';
                break;
            case 'number':
                $this->attributes['value'] = (string) $value;
                break;
            case 'json':
                $this->attributes['value'] = json_encode($value);
                break;
            case 'array':
                $this->attributes['value'] = is_array($value) ? implode(',', $value) : $value;
                break;
            default:
                $this->attributes['value'] = (string) $value;
                break;
        }
    }

    // Static methods for getting settings
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->value = $value;
            return $setting->save();
        }
        
        return false;
    }

    public static function getGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key')->toArray();
    }

    // Convenience methods for common settings
    public static function siteName()
    {
        return self::getValue('site_name', 'Sewa Kamera Pro');
    }

    public static function siteEmail()
    {
        return self::getValue('site_email', 'admin@sewakamerapro.com');
    }

    public static function sitePhone()
    {
        return self::getValue('site_phone', '0812-3456-7890');
    }

    public static function midtransServerKey()
    {
        return self::getValue('midtrans_server_key');
    }

    public static function midtransClientKey()
    {
        return self::getValue('midtrans_client_key');
    }

    public static function midtransProduction()
    {
        return self::getValue('midtrans_production', false);
    }

    public static function defaultShippingFee()
    {
        return self::getValue('default_shipping_fee', 15000);
    }

    public static function minRentalDays()
    {
        return self::getValue('min_rental_days', 1);
    }

    public static function maxRentalDays()
    {
        return self::getValue('max_rental_days', 30);
    }

    public static function depositPercentage()
    {
        return self::getValue('deposit_percentage', 50);
    }

    public static function overdueFeePercentage()
    {
        return self::getValue('overdue_fee_percentage', 10);
    }
}