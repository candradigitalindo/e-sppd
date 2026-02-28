<?php

namespace App\Repositories\Eloquent;

use App\Models\Approval;
use App\Repositories\Interfaces\ApprovalRepositoryInterface;

class ApprovalRepository extends BaseRepository implements ApprovalRepositoryInterface
{
    public function __construct(Approval $model)
    {
        parent::__construct($model);
    }

    public function getPendingByUser(string $userId)
    {
        return $this->model->where('user_id', $userId)
            ->where('status', 'menunggu')
            ->with(['approvable'])
            ->latest()
            ->get();
    }

    public function approve(string $id, string $userId, ?string $catatan = null): bool
    {
        return (bool) $this->model->where('id', $id)->update([
            'status'       => 'disetujui',
            'user_id'      => $userId,
            'catatan'      => $catatan,
            'approved_at'  => now(),
        ]);
    }

    public function reject(string $id, string $userId, string $catatan): bool
    {
        return (bool) $this->model->where('id', $id)->update([
            'status'       => 'ditolak',
            'user_id'      => $userId,
            'catatan'      => $catatan,
            'approved_at'  => now(),
        ]);
    }

    public function getByApprovable(string $type, string $id)
    {
        return $this->model->where('approvable_type', $type)
            ->where('approvable_id', $id)
            ->with('user')
            ->orderBy('urutan')
            ->get();
    }

    public function getHistoryByUser(string $userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->with('approvable')
            ->latest('approved_at')
            ->paginate(15);
    }
}
