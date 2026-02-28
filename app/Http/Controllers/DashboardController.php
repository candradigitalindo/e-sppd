<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\LaporanPerjalananRepositoryInterface;
use App\Repositories\Interfaces\SppdRepositoryInterface;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private LaporanPerjalananRepositoryInterface $laporanRepo,
        private SppdRepositoryInterface $sppdRepo,
        private ProgramKegiatanRepositoryInterface $programRepo,
    ) {}

    public function index(): Response
    {
        $tahun = (int) date('Y');

        $stats      = $this->laporanRepo->getDashboardStats($tahun);
        $berlangsung = $this->sppdRepo->getByStatus('berlangsung');
        $totalPagu  = $this->programRepo->getTotalPagu($tahun);

        return Inertia::render('Dashboard/Index', [
            'stats'       => $stats,
            'berlangsung' => $berlangsung,
            'totalPagu'   => $totalPagu,
            'tahun'       => $tahun,
        ]);
    }

    public function monitoringAnggaran(): Response
    {
        $tahun = (int) request('tahun', date('Y'));

        return Inertia::render('Dashboard/MonitoringAnggaran', [
            'tahun'    => $tahun,
            'programs' => $this->programRepo->getByTahun($tahun),
        ]);
    }
}
