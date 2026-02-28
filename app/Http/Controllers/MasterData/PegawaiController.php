<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\GolonganRepositoryInterface;
use App\Repositories\Interfaces\JabatanRepositoryInterface;
use App\Repositories\Interfaces\PegawaiRepositoryInterface;
use App\Repositories\Interfaces\UnitKerjaRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function __construct(
        private PegawaiRepositoryInterface $repo,
        private UnitKerjaRepositoryInterface $unitKerjaRepo,
        private JabatanRepositoryInterface $jabatanRepo,
        private GolonganRepositoryInterface $golonganRepo,
    ) {}

    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $data    = $keyword
            ? $this->repo->search($keyword, ['nip', 'nama', 'email'], 15)
            : $this->repo->allWithPaginate(15, ['*'], ['jabatan', 'golongan', 'unitKerja']);

        return Inertia::render('MasterData/Pegawai/Index', [
            'pegawais'   => $data,
            'filters'    => $request->only(['q']),
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/Pegawai/Form', [
            'unitKerjas' => $this->unitKerjaRepo->all(),
            'jabatans'   => $this->jabatanRepo->getForDropdown(),
            'golongans'  => $this->golonganRepo->getForDropdown(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'          => 'nullable|string|max:20|unique:pegawais,nip',
            'nama'         => 'required|string|max:100',
            'unit_kerja_id'=> 'nullable|exists:unit_kerjas,id',
            'jabatan_id'   => 'nullable|exists:jabatans,id',
            'golongan_id'  => 'nullable|exists:golongans,id',
            'no_rekening'  => 'nullable|string|max:30',
            'nama_bank'    => 'nullable|string|max:50',
            'npwp'         => 'nullable|string|max:20',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pegawai = $this->repo->findById($id, ['*'], ['jabatan', 'golongan', 'unitKerja', 'sppds']);

        return Inertia::render('MasterData/Pegawai/Show', compact('pegawai'));
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/Pegawai/Form', [
            'pegawai'    => $this->repo->findById($id),
            'unitKerjas' => $this->unitKerjaRepo->all(),
            'jabatans'   => $this->jabatanRepo->getForDropdown(),
            'golongans'  => $this->golonganRepo->getForDropdown(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nip'          => "nullable|string|max:20|unique:pegawais,nip,{$id}",
            'nama'         => 'required|string|max:100',
            'unit_kerja_id'=> 'nullable|exists:unit_kerjas,id',
            'jabatan_id'   => 'nullable|exists:jabatans,id',
            'golongan_id'  => 'nullable|exists:golongans,id',
            'no_rekening'  => 'nullable|string|max:30',
            'nama_bank'    => 'nullable|string|max:50',
            'npwp'         => 'nullable|string|max:20',
            'no_hp'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
