<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Repositories\Interfaces\ApprovalRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalRepositoryInterface $repo) {}

    public function index()
    {
        return Inertia::render('Approval/Index', [
            'pending' => $this->repo->getPendingByUser(Auth::id()),
            'history' => $this->repo->getHistoryByUser(Auth::id()),
        ]);
    }

    public function approve(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'nullable|string',
        ]);

        /** @var Approval $approval */
        $approval = Approval::findOrFail($id);

        // Pastikan user yang sedang login adalah approver yang dituju
        if ($approval->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berwenang menyetujui dokumen ini.');
        }

        $this->repo->approve($id, Auth::id(), $request->catatan);

        // Jika ini approval level 1 (Atasan), ciptakan approval level 2 (KPA) secara otomatis
        if ((int) $approval->level === 1) {
            $approvable = $approval->approvable;

            // Ambil kpa_id dari dokumen SPPD jika tersedia
            $kpaUserId = match (true) {
                $approvable instanceof \App\Models\Sppd => $approvable->kpa_id,
                default => null,
            };

            if ($kpaUserId) {
                Approval::create([
                    'approvable_type' => $approval->approvable_type,
                    'approvable_id'   => $approval->approvable_id,
                    'user_id'         => $kpaUserId,
                    'urutan'          => ($approval->urutan ?? 1) + 1,
                    'jabatan_approver' => 'KPA',
                    'status'          => 'pending',
                    'level'           => 2,
                    'parent_id'       => $approval->id,
                    'batas_waktu'     => now()->addDays(3)->toDateString(),
                ]);
            }
        }

        return back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function reject(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        /** @var Approval $approval */
        $approval = Approval::findOrFail($id);

        if ($approval->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berwenang menolak dokumen ini.');
        }

        $this->repo->reject($id, Auth::id(), $request->catatan);

        return back()->with('error', 'Dokumen ditolak.');
    }
}

