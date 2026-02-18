<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            $redirect = $request->get('redirect', route('dashboard'));
            return redirect($redirect);
        }
        $website_name = Setting::get('website_name', config('app.name'));
        return view('auth.login', ['websiteName' => $website_name]);
    }

    public function login(Request $request)
    {
        // Defensive: give a clear error when users table/columns are missing on the host
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('users') || !\Illuminate\Support\Facades\Schema::hasColumn('users', 'email')) {
                return back()->withErrors(['email' => 'Database schema belum lengkap di server — jalankan migrations (admin).']);
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Koneksi database bermasalah — periksa konfigurasi DB.']);
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $redirect = $request->get('redirect', route('dashboard'));
            return redirect()->intended($redirect);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Defensive: ensure users table exists and has email column before running validation
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('users') || !\Illuminate\Support\Facades\Schema::hasColumn('users', 'email')) {
                return back()->withErrors(['email' => 'Tabel users belum tersedia di server — jalankan migrations (admin).']);
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Koneksi database bermasalah — periksa konfigurasi DB.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nama_toko' => 'nullable|string|max:255',
            'alamat_toko' => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default role for public registration
        ]);

        // Create toko record if user supplied shop info (guard if toko table missing)
        if ((!empty($validated['nama_toko']) || !empty($validated['alamat_toko'])) && \Illuminate\Support\Facades\Schema::hasTable('toko')) {
            try {
                Toko::create([
                    'nama_toko' => $validated['nama_toko'] ?? $user->name . "'s Toko",
                    'alamat' => $validated['alamat_toko'] ?? '',
                    'user_id' => $user->id,
                ]);
            } catch (\Throwable $e) {
                // swallow — toko will be created after migrations are applied on the server
            }
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
