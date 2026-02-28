<?php

namespace App\Http\Controllers\PengajuanDana;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PengajuanUangMukaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PengajuanUangMukaController extends Controller
{
    public function __construct(private PengajuanUangMukaRepositoryInterface $repo) {}

    public function index(Request $request)
    {
        $status = $request->get('status');
        $data   = $status
            ? $this->repo->getByStatus($status)
            : $this->repo->allWithPaginate(15, ['*'], ['pegawai', 'sppd.kotaTujuan']);

        return Inertia::render('PengajuanDana/Index', [
            'pengajuans' => $data,
            'filters'    => $request->only(['status']),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('PengajuanDana/Form', [
            'sppdId' => $request->get('sppd_id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sppd_id'          => 'required|exists:sppds,id',
            'pegawai_id'       => 'required|exists:pegawais,id',
            'jumlah_pengajuan' => 'required|numeric|min:1000',
            'tanggal_pengajuan'=> 'required|date',
            'catatan'          => 'nullable|string',
        ]);

        $this->repo->create($validated);

        return redirect()->route('pengajuan-dana.index')
            ->with('success', 'Pengajuan uang muka berhasil diajukan.');
    }

    public function show(string $id)
    {
        return Inertia::render('PengajuanDana/Show', [
            'pengajuan' => $this->repo->findById($id, ['*'], ['pegawai', 'sppd.kotaTujuan', 'disetujuiOleh']),
        ]);
    }

    public function approve(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'nullable|string',
        ]);

        $this->repo->updateStatus($id, 'disetujui', Auth::id(), $request->catatan);

        return back()->with('success', 'Pengajuan uang muka disetujui.');
    }

    public function reject(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $this->repo->updateStatus($id, 'ditolak', Auth::id(), $request->catatan);

        return back()->with('error', 'Pengajuan uang muka ditolak.');
    }
}
