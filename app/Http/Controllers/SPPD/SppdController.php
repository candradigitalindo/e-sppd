<?php

namespace App\Http\Controllers\SPPD;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use App\Repositories\Interfaces\PegawaiRepositoryInterface;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use App\Repositories\Interfaces\SppdRepositoryInterface;
use App\Repositories\Interfaces\SuratTugasRepositoryInterface;
use App\Services\PerhitunganBiayaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SppdController extends Controller
{
    public function __construct(
        private SppdRepositoryInterface $repo,
        private SuratTugasRepositoryInterface $sptRepo,
        private PegawaiRepositoryInterface $pegawaiRepo,
        private KotaTujuanRepositoryInterface $kotaRepo,
        private ProgramKegiatanRepositoryInterface $programRepo,
        private PerhitunganBiayaService $biayaService,
    ) {}

    public function index(Request $request)
    {
        $data = $this->repo->getForMonitoring($request->only(['status', 'pegawai_id', 'tahun', 'bulan']));

        return Inertia::render('SPPD/Index', [
            'sppds'   => $data,
            'filters' => $request->only(['status', 'tahun', 'bulan']),
        ]);
    }

    public function create()
    {
        $tahun = (int) date('Y');

        return Inertia::render('SPPD/Form', [
            'nomor'       => $this->repo->generateNomor(),
            'sptList'     => $this->sptRepo->getByStatus('aktif'),
            'pegawais'    => $this->pegawaiRepo->getActiveWithRelations(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
            'programs'    => $this->programRepo->getForDropdown($tahun),
            'jenisPerjalananOptions' => [
                ['value' => 'dalam_negeri_biasa', 'label' => 'Dalam Negeri Biasa (524111)'],
                ['value' => 'dalam_kota',         'label' => 'Dalam Kota (524113)'],
                ['value' => 'paket_meeting',       'label' => 'Paket Meeting (524114)'],
                ['value' => 'luar_negeri',         'label' => 'Luar Negeri (524121)'],
                ['value' => 'pindah_tugas',        'label' => 'Pindah Tugas (524119)'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor'                    => 'required|string|max:50|unique:sppds,nomor',
            'surat_tugas_id'           => 'nullable|exists:surat_tugas,id',
            'pegawai_id'               => 'required|exists:pegawais,id',
            'program_kegiatan_id'      => 'nullable|exists:program_kegiatans,id',
            'kota_asal'                => 'required|string|max:100',
            'kota_tujuan_id'           => 'nullable|exists:kota_tujuans,id',
            'tanggal_berangkat'        => 'required|date',
            'tanggal_kembali'          => 'required|date|after_or_equal:tanggal_berangkat',
            'transportasi'             => 'required|in:pesawat,kereta,kapal,bus,kendaraan_dinas,kendaraan_pribadi',
            'keperluan'                => 'required|string',
            // Kolom regulasi PMK 113/2012
            'jenis_perjalanan'         => 'nullable|in:dalam_negeri_biasa,dalam_kota,paket_meeting,luar_negeri,pindah_tugas',
            'kode_akun'                => 'nullable|string|max:10',
            'penginapan_at_cost'       => 'nullable|boolean',
            'pejabat_pemberi_tugas_id' => 'nullable|exists:users,id',
            'kpa_id'                   => 'nullable|exists:users,id',
        ]);

        $validated['dibuat_oleh'] = Auth::id();

        // Auto-hitung deadline SPJ (5 hari kerja setelah tanggal kembali, PMK 113/2012)
        if (!empty($validated['tanggal_kembali'])) {
            $validated['deadline_spj'] = $this->biayaService
                ->hitungDeadlineSpj(Carbon::parse($validated['tanggal_kembali']))
                ->toDateString();
        }

        $sppd = $this->repo->create($validated);

        return redirect()->route('sppd.show', $sppd->id)
            ->with('success', 'SPPD berhasil dibuat.');
    }

    public function show(string $id)
    {
        $sppd = $this->repo->getWithRelations($id);
        $sppd->loadMissing('perhitunganBiaya');
        $sppd->append('total_biaya');

        return Inertia::render('SPPD/Show', [
            'sppd' => $sppd,
        ]);
    }

    public function edit(string $id)
    {
        $tahun = (int) date('Y');

        return Inertia::render('SPPD/Form', [
            'sppd'        => $this->repo->findById($id),
            'sptList'     => $this->sptRepo->getByStatus('aktif'),
            'pegawais'    => $this->pegawaiRepo->getActiveWithRelations(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
            'programs'    => $this->programRepo->getForDropdown($tahun),
            'jenisPerjalananOptions' => [
                ['value' => 'dalam_negeri_biasa', 'label' => 'Dalam Negeri Biasa (524111)'],
                ['value' => 'dalam_kota',         'label' => 'Dalam Kota (524113)'],
                ['value' => 'paket_meeting',       'label' => 'Paket Meeting (524114)'],
                ['value' => 'luar_negeri',         'label' => 'Luar Negeri (524121)'],
                ['value' => 'pindah_tugas',        'label' => 'Pindah Tugas (524119)'],
            ],
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kota_asal'                => 'required|string|max:100',
            'kota_tujuan_id'           => 'nullable|exists:kota_tujuans,id',
            'tanggal_berangkat'        => 'required|date',
            'tanggal_kembali'          => 'required|date|after_or_equal:tanggal_berangkat',
            'transportasi'             => 'required|in:pesawat,kereta,kapal,bus,kendaraan_dinas,kendaraan_pribadi',
            'keperluan'                => 'required|string',
            'jenis_perjalanan'         => 'nullable|in:dalam_negeri_biasa,dalam_kota,paket_meeting,luar_negeri,pindah_tugas',
            'kode_akun'                => 'nullable|string|max:10',
            'penginapan_at_cost'       => 'nullable|boolean',
            'pejabat_pemberi_tugas_id' => 'nullable|exists:users,id',
            'kpa_id'                   => 'nullable|exists:users,id',
        ]);

        // Recalculate deadline SPJ whenever tanggal_kembali changes
        if (!empty($validated['tanggal_kembali'])) {
            $validated['deadline_spj'] = $this->biayaService
                ->hitungDeadlineSpj(Carbon::parse($validated['tanggal_kembali']))
                ->toDateString();
        }

        $this->repo->update($id, $validated);

        return redirect()->route('sppd.show', $id)
            ->with('success', 'SPPD berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('sppd.index')
            ->with('success', 'SPPD berhasil dihapus.');
    }

    public function cetak(string $id)
    {
        $sppd = $this->repo->getWithRelations($id);
        $sppd->loadMissing(['perhitunganBiaya', 'suratTugas']);
        $sppd->append('total_biaya');

        return Inertia::render('SPPD/Cetak', compact('sppd'));
    }
}
