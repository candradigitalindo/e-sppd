<?php

namespace App\Repositories\Eloquent;

use App\Models\AuditLog;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogRepository extends BaseRepository implements AuditLogRepositoryInterface
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }

    public function getByUser(string $userId)
    {
        return $this->model->where('user_id', $userId)
            ->with('user')
            ->latest()
            ->paginate(15);
    }

    public function getByModule(string $module)
    {
        return $this->model->where('module', $module)
            ->with('user')
            ->latest()
            ->paginate(15);
    }

    public function log(string $action, string $module, ?array $dataBefore = null, ?array $dataAfter = null): void
    {
        $this->model->create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'module'      => $module,
            'data_before' => $dataBefore,
            'data_after'  => $dataAfter,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    public function cleanup(int $daysOld = 90): int
    {
        return $this->model->where('created_at', '<', now()->subDays($daysOld))->delete();
    }
}
