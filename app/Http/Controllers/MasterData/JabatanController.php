<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\JabatanRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JabatanController extends Controller
{
    public function __construct(private JabatanRepositoryInterface $repo) {}

    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $data = $keyword
            ? $this->repo->search($keyword, ['kode', 'nama'])
            : $this->repo->allWithPaginate(15);

        return Inertia::render('MasterData/Jabatan/Index', [
            'jabatans' => $data,
            'filters'  => $request->only(['q']),
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/Jabatan/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'  => 'required|string|max:20|unique:jabatans,kode',
            'nama'  => 'required|string|max:100',
            'level' => 'required|in:pimpinan_tinggi_madya,pimpinan_tinggi_pratama,administrator,pengawas,pelaksana,fungsional',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.jabatan.index')
            ->with('success', 'Jabatan berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/Jabatan/Form', [
            'jabatan' => $this->repo->findById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kode'  => "required|string|max:20|unique:jabatans,kode,{$id}",
            'nama'  => 'required|string|max:100',
            'level' => 'required|in:pimpinan_tinggi_madya,pimpinan_tinggi_pratama,administrator,pengawas,pelaksana,fungsional',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
