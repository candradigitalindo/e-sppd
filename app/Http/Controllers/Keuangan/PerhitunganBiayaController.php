<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Sppd;
use App\Repositories\Interfaces\PerhitunganBiayaRepositoryInterface;
use App\Services\PerhitunganBiayaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerhitunganBiayaController extends Controller
{
    public function __construct(
        private PerhitunganBiayaRepositoryInterface $repo,
        private PerhitunganBiayaService $biayaService,
    ) {}

    public function index(string $sppdId)
    {
        return Inertia::render('Keuangan/PerhitunganBiaya/Index', [
            'sppdId'    => $sppdId,
            'perhitungan' => $this->repo->getBySppd($sppdId),
            'total'     => $this->repo->getTotalBySppd($sppdId),
        ]);
    }

    public function hitungOtomatis(string $sppdId)
    {
        $sppd = Sppd::with(['pegawai.golongan', 'kotaTujuan', 'programKegiatan'])
            ->findOrFail($sppdId);

        try {
            $this->biayaService->hitungOtomatis($sppd);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Perhitungan biaya PMK berhasil dihitung otomatis.');
    }

    public function store(Request $request, string $sppdId)
    {
        $validated = $request->validate([
            'items'                  => 'required|array',
            'items.*.jenis'          => 'required|in:uang_harian,penginapan,transportasi,uang_representasi,transport_lokal',
            'items.*.jumlah_hari'    => 'required|integer|min:1',
            'items.*.satuan_biaya'   => 'required|numeric|min:0',
            'items.*.tipe_hari'      => 'nullable|in:full,parsial',
            'items.*.persentase'     => 'nullable|numeric|min:0|max:100',
            'items.*.at_cost'        => 'nullable|boolean',
            'items.*.kode_akun'      => 'nullable|string|max:10',
        ]);

        $items = collect($validated['items'])->map(function ($item) {
            $item['total'] = $item['jumlah_hari'] * $item['satuan_biaya'];
            return $item;
        })->all();

        $this->repo->bulkSave($sppdId, $items);

        return back()->with('success', 'Perhitungan biaya berhasil disimpan.');
    }
}
