<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProgramKegiatanRepositoryInterface;
use App\Repositories\Interfaces\UnitKerjaRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramKegiatanController extends Controller
{
    public function __construct(
        private ProgramKegiatanRepositoryInterface $repo,
        private UnitKerjaRepositoryInterface $unitKerjaRepo,
    ) {}

    public function index(Request $request)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        return Inertia::render('Perencanaan/ProgramKegiatan/Index', [
            'programs'   => $this->repo->getByTahun($tahun),
            'totalPagu'  => $this->repo->getTotalPagu($tahun),
            'tahun'      => $tahun,
            'filters'    => compact('tahun'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Perencanaan/ProgramKegiatan/Form', [
            'unitKerjas' => $this->unitKerjaRepo->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'kode'          => 'required|string|max:30',
            'nama'          => 'required|string|max:200',
            'tahun'         => 'required|integer',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        $this->repo->create($validated);

        return redirect()->route('perencanaan.program-kegiatan.index')
            ->with('success', 'Program kegiatan berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Perencanaan/ProgramKegiatan/Form', [
            'program'    => $this->repo->findById($id),
            'unitKerjas' => $this->unitKerjaRepo->all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'unit_kerja_id' => 'nullable|exists:unit_kerjas,id',
            'kode'          => 'required|string|max:30',
            'nama'          => 'required|string|max:200',
            'pagu_anggaran' => 'required|numeric|min:0',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('perencanaan.program-kegiatan.index')
            ->with('success', 'Program kegiatan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('perencanaan.program-kegiatan.index')
            ->with('success', 'Program kegiatan berhasil dihapus.');
    }
}
