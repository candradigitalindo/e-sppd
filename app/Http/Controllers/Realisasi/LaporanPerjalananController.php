<?php

namespace App\Http\Controllers\Realisasi;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LaporanPerjalananRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LaporanPerjalananController extends Controller
{
    public function __construct(private LaporanPerjalananRepositoryInterface $repo) {}

    public function index()
    {
        return Inertia::render('Realisasi/Laporan/Index', [
            'laporan' => $this->repo->allWithPaginate(15, ['*'], ['sppd.pegawai', 'sppd.kotaTujuan']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Realisasi/Laporan/Form', [
            'sppdId' => $request->get('sppd_id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sppd_id'        => 'required|exists:sppds,id|unique:laporan_perjalanans,sppd_id',
            'isi_laporan'    => 'required|string|min:50',
            'tanggal_laporan'=> 'required|date',
        ]);

        $validated['dibuat_oleh'] = Auth::id();
        $this->repo->create($validated);

        return redirect()->route('realisasi.laporan.index')
            ->with('success', 'Laporan perjalanan berhasil disimpan.');
    }

    public function show(string $id)
    {
        return Inertia::render('Realisasi/Laporan/Show', [
            'laporan' => $this->repo->findById($id, ['*'], ['sppd.pegawai.jabatan', 'sppd.kotaTujuan', 'sppd.buktiPerjalanan']),
        ]);
    }

    public function edit(string $id)
    {
        return Inertia::render('Realisasi/Laporan/Form', [
            'laporan' => $this->repo->findById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'isi_laporan'    => 'required|string|min:50',
            'tanggal_laporan'=> 'required|date',
            'status'         => 'required|in:draft,selesai',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('realisasi.laporan.show', $id)
            ->with('success', 'Laporan perjalanan berhasil diperbarui.');
    }
}
