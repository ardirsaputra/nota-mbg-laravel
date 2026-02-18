<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    // Helper untuk mendapatkan value setting
    public static function get($key, $default = null)
    {
        // Defensive: return default when the settings table or DB is not available
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return $default;
            }

            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            // Decode JSON jika type adalah json
            if ($setting->type === 'json') {
                return json_decode($setting->value, true) ?? $default;
            }

            return $setting->value ?? $default;
        } catch (\Throwable $e) {
            // If anything goes wrong (no DB connection / migrations not run), return the provided default
            return $default;
        }
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

    /**
     * Defensive wrapper so seeders can call Setting::updateOrCreate()
     * even if the Eloquent magic forwarding isn't available in some deploy targets.
     */
    public static function updateOrCreate(array $attributes, array $values = [])
    {
        // Prefer delegating to the query builder which implements the method.
        return static::query()->updateOrCreate($attributes, $values);
    }

    /**
     * Return a publicly-accessible URL for a stored file path that may reside in:
     * - public/storage/<path>
     * - public/storage/public/<path>
     * - storage disk 'public' (when public/storage is a symlink)
     *
     * Accepts null and returns $default when not found.
     */
    public static function storageUrl(?string $relativePath, $default = null)
    {
        if (empty($relativePath)) {
            return $default;
        }

        // prefer public/storage/<path>
        $publicPath = public_path('storage/' . $relativePath);
        if (file_exists($publicPath)) {
            return asset('storage/' . $relativePath);
        }

        // fallback: public/storage/public/<path> (some hosts or legacy uploads)
        $publicPath2 = public_path('storage/public/' . $relativePath);
        if (file_exists($publicPath2)) {
            return asset('storage/public/' . $relativePath);
        }

        // fallback: check storage disk (symlink scenario)
        try {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                return asset('storage/' . $relativePath);
            }
        } catch (\Throwable $e) {
            // ignore disk errors
        }

        return $default;
    }
}
