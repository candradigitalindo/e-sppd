<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function index()
    {
        return Inertia::render('Sistem/Role/Index', [
            'roles' => $this->repo->getWithPermissions(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Sistem/Role/Form', [
            'permissions' => Permission::orderBy('module')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:50',
            'slug'           => 'required|string|max:50|unique:roles,slug',
            'description'    => 'nullable|string',
            'permission_ids' => 'nullable|array',
            'permission_ids.*'=> 'exists:permissions,id',
        ]);

        $permIds = $validated['permission_ids'] ?? [];
        unset($validated['permission_ids']);

        $role = $this->repo->create($validated);
        if (! empty($permIds)) {
            $this->repo->syncPermissions($role->id, $permIds);
        }

        return redirect()->route('sistem.role.index')
            ->with('success', 'Role berhasil dibuat.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Sistem/Role/Form', [
            'role'        => $this->repo->findById($id, ['*'], ['permissions']),
            'permissions' => Permission::orderBy('module')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'           => 'required|string|max:50',
            'description'    => 'nullable|string',
            'permission_ids' => 'nullable|array',
        ]);

        $this->repo->update($id, $request->only(['name', 'description']));
        $this->repo->syncPermissions($id, $request->get('permission_ids', []));

        return redirect()->route('sistem.role.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('sistem.role.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
