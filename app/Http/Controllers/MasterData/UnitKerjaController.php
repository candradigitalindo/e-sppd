<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UnitKerjaRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitKerjaController extends Controller
{
    public function __construct(private UnitKerjaRepositoryInterface $repo) {}

    public function index()
    {
        return Inertia::render('MasterData/UnitKerja/Index', [
            'unitKerjas' => $this->repo->getTree(),
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/UnitKerja/Form', [
            'parents' => $this->repo->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id'  => 'nullable|exists:unit_kerjas,id',
            'kode'       => 'required|string|max:20|unique:unit_kerjas,kode',
            'nama'       => 'required|string|max:150',
            'tingkat'    => 'required|in:dinas,bidang,seksi,sub_bagian',
            'keterangan' => 'nullable|string',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/UnitKerja/Form', [
            'unitKerja' => $this->repo->findById($id),
            'parents'   => $this->repo->all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'parent_id'  => 'nullable|exists:unit_kerjas,id',
            'kode'       => "required|string|max:20|unique:unit_kerjas,kode,{$id}",
            'nama'       => 'required|string|max:150',
            'tingkat'    => 'required|in:dinas,bidang,seksi,sub_bagian',
            'keterangan' => 'nullable|string',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.unit-kerja.index')
            ->with('success', 'Unit kerja berhasil dihapus.');
    }
}
