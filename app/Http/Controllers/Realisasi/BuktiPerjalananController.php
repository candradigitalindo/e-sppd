<?php

namespace App\Http\Controllers\Realisasi;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BuktiPerjalananRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BuktiPerjalananController extends Controller
{
    public function __construct(private BuktiPerjalananRepositoryInterface $repo) {}

    public function index(string $sppdId)
    {
        return Inertia::render('Realisasi/Bukti/Index', [
            'sppdId' => $sppdId,
            'buktis' => $this->repo->getBySppd($sppdId),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sppd_id'    => 'required|exists:sppds,id',
            'jenis'      => 'required|in:tiket_berangkat,tiket_pulang,boarding_pass,kwitansi_hotel,kwitansi_transport,dokumen_lain',
            'file'       => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
            'keterangan' => 'nullable|string',
        ]);

        $file     = $request->file('file');
        $path     = $file->store("bukti/{$request->sppd_id}", 'public');

        $this->repo->create([
            'sppd_id'    => $request->sppd_id,
            'jenis'      => $request->jenis,
            'nama_file'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Bukti perjalanan berhasil diunggah.');
    }

    public function destroy(string $id)
    {
        $this->repo->deleteFile($id);

        return back()->with('success', 'Bukti perjalanan berhasil dihapus.');
    }
}
