<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\GolonganRepositoryInterface;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use App\Repositories\Interfaces\StandarBiayaRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandarBiayaController extends Controller
{
    public function __construct(
        private StandarBiayaRepositoryInterface $repo,
        private GolonganRepositoryInterface $golonganRepo,
        private KotaTujuanRepositoryInterface $kotaRepo,
    ) {}

    public function index(Request $request)
    {
        $tahun = (int) $request->get('tahun', date('Y'));

        return Inertia::render('MasterData/StandarBiaya/Index', [
            'standarBiayas' => $this->repo->getByTahun($tahun),
            'tahun'         => $tahun,
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/StandarBiaya/Form', [
            'golongans'   => $this->golonganRepo->getForDropdown(),
            'kotaTujuans' => $this->kotaRepo->getForDropdown(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'golongan_id'       => 'required|exists:golongans,id',
            'kota_tujuan_id'    => 'required|exists:kota_tujuans,id',
            'tahun'             => 'required|integer|min:2020|max:2099',
            'uang_harian'       => 'required|numeric|min:0',
            'penginapan'        => 'required|numeric|min:0',
            'transportasi'      => 'required|numeric|min:0',
            'uang_representasi' => 'nullable|numeric|min:0',
            'transport_lokal'   => 'nullable|numeric|min:0',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.standar-biaya.index')
            ->with('success', 'Standar biaya berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/StandarBiaya/Form', [
            'standarBiaya' => $this->repo->findById($id),
            'golongans'    => $this->golonganRepo->getForDropdown(),
            'kotaTujuans'  => $this->kotaRepo->getForDropdown(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'uang_harian'       => 'required|numeric|min:0',
            'penginapan'        => 'required|numeric|min:0',
            'transportasi'      => 'required|numeric|min:0',
            'uang_representasi' => 'nullable|numeric|min:0',
            'transport_lokal'   => 'nullable|numeric|min:0',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.standar-biaya.index')
            ->with('success', 'Standar biaya berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.standar-biaya.index')
            ->with('success', 'Standar biaya berhasil dihapus.');
    }
}
