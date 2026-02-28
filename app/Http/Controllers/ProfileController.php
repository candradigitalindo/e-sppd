<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Show profile edit page.
     */
    public function edit(Request $request)
    {
        $user = $request->user()->load('role', 'pegawai.jabatan', 'pegawai.golongan', 'pegawai.unitKerja');

        return Inertia::render('Profile/Edit', [
            'user' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $user->role?->only(['id', 'name']),
                'pegawai'  => $user->pegawai ? [
                    'nip'        => $user->pegawai->nip,
                    'nama'       => $user->pegawai->nama,
                    'no_hp'      => $user->pegawai->no_hp,
                    'email'      => $user->pegawai->email,
                    'jabatan'    => $user->pegawai->jabatan?->nama,
                    'golongan'   => $user->pegawai->golongan?->nama,
                    'unit_kerja' => $user->pegawai->unitKerja?->nama,
                ] : null,
            ],
        ]);
    }

    /**
     * Update profile info (name & email).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
