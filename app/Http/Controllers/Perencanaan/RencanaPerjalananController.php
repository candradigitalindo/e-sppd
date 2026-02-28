<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use App\Repositories\Interfaces\PegawaiRepositoryInterface;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use App\Repositories\Interfaces\RencanaPerjalananRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RencanaPerjalananController extends Controller
{
    public function __construct(
        private RencanaPerjalananRepositoryInterface $repo,
        private PegawaiRepositoryInterface $pegawaiRepo,
        private KotaTujuanRepositoryInterface $kotaRepo,
        private ProgramKegiatanRepositoryInterface $programRepo,
    ) {}

    public function index(Request $request)
    {
        $status  = $request->get('status');
        $data    = $status
            ? $this->repo->getByStatus($status)
            : $this->repo->allWithPaginate(15, ['*'], ['pegawai', 'kotaTujuan']);

        return Inertia::render('Perencanaan/RencanaPerjalanan/Index', [
            'rencanas' => $data,
            'filters'  => $request->only(['status']),
        ]);
    }

    public function create()
    {
        $tahun = (int) date('Y');

        return Inertia::render('Perencanaan/RencanaPerjalanan/Form', [
            'pegawais'    => $this->pegawaiRepo->getActiveWithRelations(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
            'programs'    => $this->programRepo->getForDropdown($tahun),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'          => 'required|exists:pegawais,id',
            'kota_tujuan_id'      => 'nullable|exists:kota_tujuans,id',
            'program_kegiatan_id' => 'nullable|exists:program_kegiatans,id',
            'keperluan'           => 'required|string|max:255',
            'tanggal_rencana'     => 'required|date',
            'estimasi_hari'       => 'required|integer|min:1',
        ]);

        $this->repo->create($validated);

        return redirect()->route('perencanaan.rencana-perjalanan.index')
            ->with('success', 'Rencana perjalanan berhasil disimpan.');
    }

    public function show(string $id)
    {
        return Inertia::render('Perencanaan/RencanaPerjalanan/Show', [
            'rencana' => $this->repo->findById($id, ['*'], ['pegawai.jabatan', 'kotaTujuan', 'programKegiatan']),
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status'  => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $this->repo->updateStatus($id, $request->status, $request->catatan);

        return back()->with('success', 'Status rencana perjalanan diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('perencanaan.rencana-perjalanan.index')
            ->with('success', 'Rencana perjalanan berhasil dihapus.');
    }
}
