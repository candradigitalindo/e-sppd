<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\KotaTujuanRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KotaTujuanController extends Controller
{
    public function __construct(private KotaTujuanRepositoryInterface $repo) {}

    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $data = $keyword
            ? $this->repo->search($keyword, ['kode', 'nama', 'provinsi'])
            : $this->repo->allWithPaginate(15);

        return Inertia::render('MasterData/KotaTujuan/Index', [
            'kotaTujuans' => $data,
            'filters'     => $request->only(['q']),
        ]);
    }

    public function create()
    {
        return Inertia::render('MasterData/KotaTujuan/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'          => 'required|string|max:20|unique:kota_tujuans,kode',
            'nama'          => 'required|string|max:100',
            'provinsi'      => 'required|string|max:100',
            'kategori_biaya'=> 'required|in:A,B,C,D',
        ]);

        $this->repo->create($validated);

        return redirect()->route('master-data.kota-tujuan.index')
            ->with('success', 'Kota tujuan berhasil disimpan.');
    }

    public function edit(string $id)
    {
        return Inertia::render('MasterData/KotaTujuan/Form', [
            'kotaTujuan' => $this->repo->findById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kode'          => "required|string|max:20|unique:kota_tujuans,kode,{$id}",
            'nama'          => 'required|string|max:100',
            'provinsi'      => 'required|string|max:100',
            'kategori_biaya'=> 'required|in:A,B,C,D',
        ]);

        $this->repo->update($id, $validated);

        return redirect()->route('master-data.kota-tujuan.index')
            ->with('success', 'Kota tujuan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('master-data.kota-tujuan.index')
            ->with('success', 'Kota tujuan berhasil dihapus.');
    }
}
