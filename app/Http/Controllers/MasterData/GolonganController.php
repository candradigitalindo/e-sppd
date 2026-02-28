<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\GolonganRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GolonganController extends Controller
{
    public function __construct(private GolonganRepositoryInterface $repo) {}

    public function index(Request $request)
    {
        return Inertia::render('MasterData/Golongan/Index', [
            'golongans' => $this->repo->allWithPaginate(15),
            'filters'   => $request->only(['q']),
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/Golongan/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:10|unique:golongans,kode',
            'nama'       => 'required|string|max:50',
            'ruang'      => 'nullable|string|max:5',
            'keterangan' => 'nullable|string',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.golongan.index')
            ->with('success', 'Golongan berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/Golongan/Form', [
            'golongan' => $this->repo->findById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kode'       => "required|string|max:10|unique:golongans,kode,{$id}",
            'nama'       => 'required|string|max:50',
            'ruang'      => 'nullable|string|max:5',
            'keterangan' => 'nullable|string',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.golongan.index')
            ->with('success', 'Golongan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.golongan.index')
            ->with('success', 'Golongan berhasil dihapus.');
    }
}
