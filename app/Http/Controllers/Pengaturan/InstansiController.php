<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InstansiController extends Controller
{
    public function edit()
    {
        return Inertia::render('Pengaturan/Instansi', [
            'instansi' => Instansi::getSingleton(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:200',
            'singkatan'         => 'nullable|string|max:50',
            'alamat'            => 'nullable|string|max:500',
            'kota'              => 'nullable|string|max:100',
            'telepon'           => 'nullable|string|max:50',
            'fax'               => 'nullable|string|max:50',
            'website'           => 'nullable|string|max:150',
            'email'             => 'nullable|email|max:100',
            'kode_pos'          => 'nullable|string|max:10',
            'pejabat_nama'      => 'nullable|string|max:150',
            'pejabat_jabatan'   => 'nullable|string|max:150',
            'pejabat_nip'       => 'nullable|string|max:30',
            'pejabat_pangkat'   => 'nullable|string|max:100',
            'logo_posisi'       => 'required|in:kiri,kanan,atas',
            'logo1'             => 'nullable|image|mimes:png,jpg,jpeg,svg+xml|max:2048',
            'logo2'             => 'nullable|image|mimes:png,jpg,jpeg,svg+xml|max:2048',
            'hapus_logo1'       => 'nullable|boolean',
            'hapus_logo2'       => 'nullable|boolean',
        ]);

        $instansi = Instansi::getSingleton();

        // Handle logo 1
        if ($request->boolean('hapus_logo1')) {
            $instansi->deleteLogo(1);
        } elseif ($request->hasFile('logo1')) {
            if ($instansi->logo1) {
                Storage::delete($instansi->logo1);
            }
            $validated['logo1'] = $request->file('logo1')->store('instansi', 'public');
        } else {
            unset($validated['logo1']);
        }

        // Handle logo 2
        if ($request->boolean('hapus_logo2')) {
            $instansi->deleteLogo(2);
        } elseif ($request->hasFile('logo2')) {
            if ($instansi->logo2) {
                Storage::delete($instansi->logo2);
            }
            $validated['logo2'] = $request->file('logo2')->store('instansi', 'public');
        } else {
            unset($validated['logo2']);
        }

        // Remove file fields before fill (already handled above)
        unset($validated['hapus_logo1'], $validated['hapus_logo2']);

        $instansi->update($validated);

        // Refresh shared instansi cache
        cache()->forget('instansi_singleton');

        return redirect()->back()->with('success', 'Data instansi berhasil disimpan.');
    }
}
