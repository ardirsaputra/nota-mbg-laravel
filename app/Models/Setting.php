<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    // Helper untuk mendapatkan value setting
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Decode JSON jika type adalah json
        if ($setting->type === 'json') {
            return json_decode($setting->value, true) ?? $default;
        }

        return $setting->value ?? $default;
    }

    // Helper untuk set value setting
    public static function set($key, $value, $type = 'text')
    {
        // Encode ke JSON jika value adalah array
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
