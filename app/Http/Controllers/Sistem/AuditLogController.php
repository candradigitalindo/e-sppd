<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function __construct(private AuditLogRepositoryInterface $repo) {}

    public function index(Request $request)
    {
        $module = $request->get('module');
        $data   = $module
            ? $this->repo->getByModule($module)
            : $this->repo->allWithPaginate(20, ['*'], ['user']);

        return Inertia::render('Sistem/AuditLog/Index', [
            'logs'    => $data,
            'filters' => $request->only(['module']),
        ]);
    }
}
