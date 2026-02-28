<?php

namespace App\Http\Controllers\SPJ;

use App\Http\Controllers\Controller;
use App\Models\Sppd;
use App\Repositories\Interfaces\RekapPengeluaranRepositoryInterface;
use App\Repositories\Interfaces\SpjRepositoryInterface;
use App\Services\PerhitunganBiayaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SpjController extends Controller
{
    public function __construct(
        private SpjRepositoryInterface $repo,
        private RekapPengeluaranRepositoryInterface $rekapRepo,
        private PerhitunganBiayaService $biayaService,
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status');
        $data   = $status
            ? $this->repo->getByStatus($status)
            : $this->repo->allWithPaginate(15, ['*'], ['sppd.pegawai']);

        return Inertia::render('SPJ/Index', [
            'spjs'    => $data,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('SPJ/Form', [
            'nomor'  => $this->repo->generateNomor(),
            'sppdId' => $request->get('sppd_id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sppd_id'        => 'required|exists:sppds,id|unique:spjs,sppd_id',
            'nomor'          => 'required|string|max:50|unique:spjs,nomor',
            'tanggal'        => 'required|date',
            'total_rencana'  => 'required|numeric|min:0',
            'total_realisasi'=> 'required|numeric|min:0',
            'catatan'        => 'nullable|string',
            'rekap'          => 'required|array',
            'rekap.*.jenis'             => 'required|string',
            'rekap.*.jumlah_rencana'    => 'required|numeric|min:0',
            'rekap.*.jumlah_realisasi'  => 'required|numeric|min:0',
        ]);

        $rekap = $validated['rekap'];
        unset($validated['rekap']);
        $validated['sisa_anggaran'] = $validated['total_rencana'] - $validated['total_realisasi'];
        $validated['dibuat_oleh']   = Auth::id();

        // Set batas penyerahan dari tanggal kembali SPPD (5 hari kerja, PMK 113/2012)
        $sppd = Sppd::findOrFail($validated['sppd_id']);
        $batasPenyerahan = $this->biayaService->hitungDeadlineSpj($sppd->tanggal_kembali);
        $validated['batas_penyerahan'] = $batasPenyerahan->toDateString();
        $validated['terlambat'] = now()->toDateString() > $batasPenyerahan->toDateString();

        $spj = $this->repo->create($validated);
        $this->rekapRepo->bulkSave($spj->id, $rekap);

        return redirect()->route('spj.show', $spj->id)
            ->with('success', 'SPJ berhasil dibuat.');
    }

    public function show(string $id)
    {
        return Inertia::render('SPJ/Show', [
            'spj' => $this->repo->getWithRekapPengeluaran($id),
        ]);
    }

    public function cetak(string $id)
    {
        return Inertia::render('SPJ/Cetak', [
            'spj' => $this->repo->getWithRekapPengeluaran($id),
        ]);
    }
}
