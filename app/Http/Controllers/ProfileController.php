<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Toko;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $toko = Toko::where('user_id', $user->id)->first();

        return view('profile.edit', compact('user', 'toko'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user  = User::find($user->id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'nama_toko' => 'nullable|string|max:255',
            'alamat_toko' => 'nullable|string|max:1000',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update or create toko for this user
        $toko = Toko::where('user_id', $user->id)->first();
        if ($toko) {
            $toko->update([
                'nama_toko' => $validated['nama_toko'] ?? $toko->nama_toko,
                'alamat' => $validated['alamat_toko'] ?? $toko->alamat,
            ]);
        } else {
            if (!empty($validated['nama_toko']) || !empty($validated['alamat_toko'])) {
                Toko::create([
                    'nama_toko' => $validated['nama_toko'] ?? $user->name . "'s Toko",
                    'alamat' => $validated['alamat_toko'] ?? '',
                    'user_id' => $user->id,
                ]);
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
