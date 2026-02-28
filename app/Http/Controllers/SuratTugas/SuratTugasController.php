<?php

namespace App\Http\Controllers\SuratTugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use App\Repositories\Interfaces\PegawaiRepositoryInterface;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use App\Repositories\Interfaces\SuratTugasRepositoryInterface;
use App\Services\NomorDokumenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SuratTugasController extends Controller
{
    public function __construct(
        private SuratTugasRepositoryInterface $repo,
        private PegawaiRepositoryInterface $pegawaiRepo,
        private KotaTujuanRepositoryInterface $kotaRepo,
        private ProgramKegiatanRepositoryInterface $programRepo,
        private NomorDokumenService $nomorService,
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status');
        $data   = $status
            ? $this->repo->getByStatus($status)
            : $this->repo->getWithPegawai();

        return Inertia::render('SuratTugas/Index', [
            'suratTugas' => $data,
            'filters'    => $request->only(['status']),
        ]);
    }

    public function create()
    {
        $tahun = (int) date('Y');
        $bulan = (int) date('n');
        /** @var User $user */
        $user  = Auth::user();
        $user->load('pegawai');
        $unitKerjaId = $user->pegawai?->unit_kerja_id;

        $nomor = $unitKerjaId
            ? $this->nomorService->preview('SPT', $unitKerjaId, $tahun, $bulan)
            : $this->repo->generateNomor();

        return Inertia::render('SuratTugas/Form', [
            'nomor'       => $nomor,
            'pegawais'    => $this->pegawaiRepo->getActiveWithRelations(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
            'programs'    => $this->programRepo->getForDropdown($tahun),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor'               => 'nullable|string|max:50|unique:surat_tugas,nomor',
            'tanggal'             => 'required|date',
            'program_kegiatan_id' => 'nullable|exists:program_kegiatans,id',
            'dasar_hukum'         => 'nullable|string|max:255',
            'keperluan'           => 'required|string',
            'kota_asal'           => 'required|string|max:100',
            'kota_tujuan_id'      => 'nullable|exists:kota_tujuans,id',
            'tanggal_berangkat'   => 'required|date',
            'tanggal_kembali'     => 'required|date|after_or_equal:tanggal_berangkat',
            'pegawai_ids'         => 'required|array|min:1',
            'pegawai_ids.*'       => 'exists:pegawais,id',
        ]);

        $pegawaiIds = $validated['pegawai_ids'];
        unset($validated['pegawai_ids']);
        $validated['dibuat_oleh'] = Auth::id();

        // Auto-generate nomor dokumen per Permendagri 54/2009 (Tata Naskah Dinas)
        if (empty($validated['nomor'])) {
            /** @var User $user */
            $user = Auth::user();
            $user->load('pegawai');
            $unitKerjaId = $user->pegawai?->unit_kerja_id;
            $tanggal = \Carbon\Carbon::parse($validated['tanggal']);

            $validated['nomor'] = $unitKerjaId
                ? $this->nomorService->generate('SPT', $unitKerjaId, $tanggal->year, $tanggal->month)
                : $this->repo->generateNomor();
        }

        $spt = $this->repo->create($validated);
        $this->repo->tambahPegawai($spt->id, $pegawaiIds);

        return redirect()->route('surat-tugas.show', $spt->id)
            ->with('success', 'Surat tugas berhasil dibuat.');
    }

    public function show(string $id)
    {
        $spt = $this->repo->findById($id, ['*'], ['pegawais.jabatan', 'pegawais.golongan', 'kotaTujuan', 'programKegiatan', 'sppds']);

        return Inertia::render('SuratTugas/Show', compact('spt'));
    }

    public function edit(string $id)
    {
        $tahun = (int) date('Y');

        return Inertia::render('SuratTugas/Form', [
            'spt'         => $this->repo->findById($id, ['*'], ['pegawais']),
            'pegawais'    => $this->pegawaiRepo->getActiveWithRelations(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
            'programs'    => $this->programRepo->getForDropdown($tahun),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'keperluan'        => 'required|string',
            'kota_asal'        => 'required|string|max:100',
            'tanggal_berangkat'=> 'required|date',
            'tanggal_kembali'  => 'required|date|after_or_equal:tanggal_berangkat',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('surat-tugas.show', $id)
            ->with('success', 'Surat tugas berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('surat-tugas.index')
            ->with('success', 'Surat tugas berhasil dihapus.');
    }

    public function cetak(string $id)
    {
        $spt = $this->repo->findById($id, ['*'], ['pegawais.jabatan', 'pegawais.golongan', 'kotaTujuan']);

        return Inertia::render('SuratTugas/Cetak', compact('spt'));
    }
}
