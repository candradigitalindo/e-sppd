<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Sppd;
use App\Models\ProgramKegiatan;
use App\Repositories\Interfaces\SppdRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function __construct(private SppdRepositoryInterface $sppdRepo) {}

    public function perjalananPegawai(Request $request)
    {
        $filters = $request->only(['pegawai_id', 'tahun', 'bulan', 'status']);

        return Inertia::render('Laporan/PerjalananPegawai', [
            'sppds'   => $this->sppdRepo->getForMonitoring($filters),
            'filters' => $filters,
        ]);
    }

    public function penggunaanAnggaran(Request $request)
    {
        $tahun    = (int) $request->get('tahun', date('Y'));
        $programs = ProgramKegiatan::where('tahun', $tahun)
            ->addSelect([
                'total_realisasi' => DB::table('perhitungan_biayas')
                    ->join('sppds', 'sppds.id', '=', 'perhitungan_biayas.sppd_id')
                    ->whereColumn('sppds.program_kegiatan_id', 'program_kegiatans.id')
                    ->whereNull('sppds.deleted_at')
                    ->selectRaw('COALESCE(SUM(perhitungan_biayas.total), 0)'),
            ])
            ->get();

        return Inertia::render('Laporan/PenggunaanAnggaran', [
            'programs' => $programs,
            'tahun'    => $tahun,
        ]);
    }

    public function rekapTahunan(Request $request)
    {
        $tahun = (int) $request->get('tahun', date('Y'));
        $data  = Sppd::whereYear('tanggal_berangkat', $tahun)
            ->with(['pegawai.golongan', 'kotaTujuan'])
            ->get();

        return Inertia::render('Laporan/RekapTahunan', [
            'sppds' => $data,
            'tahun' => $tahun,
        ]);
    }
}
