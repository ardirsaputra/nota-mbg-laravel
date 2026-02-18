<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure DB connection uses GMT+7 for MySQL/MariaDB connections
        try {
            DB::statement("SET time_zone = '+07:00'");
        } catch (\Throwable $e) {
            // ignore if DB not available during some artisan commands
        }

        // Prevent "Specified key was too long" on older MySQL (<5.7.7) / MariaDB hosts
        try {
            Schema::defaultStringLength(191);
        } catch (\Throwable $e) {
            // ignore when Schema facade not available during early bootstrap
        }

        // Fallback shims for partially-deployed hosts:
        // If certain model files (Setting, Gallery) are missing on the server
        // provide minimal no-op implementations so views/controllers don't fatally error.
        try {
            if (!class_exists(\App\Models\Setting::class)) {
                eval ('namespace App\\Models; class Setting { public static function get($key, $default = null) { return $default; } public static function set($key, $value, $type = "text") { return null; } }');
            }

            if (!class_exists(\App\Models\Gallery::class)) {
                eval ('namespace App\\Models; class Gallery { public static function ordered() { return collect(); } public static function max($col) { return null; } public static function create($arr) { return null; } }');
            }
        } catch (\Throwable $e) {
            // swallow any bootstrap-time errors from the shims
        }
    }
}
