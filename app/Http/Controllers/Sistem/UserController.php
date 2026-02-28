<?php

namespace App\Http\Controllers\Sistem;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $repo,
        private RoleRepositoryInterface $roleRepo,
    ) {}

    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $data    = $keyword
            ? $this->repo->search($keyword, ['name', 'email'])
            : $this->repo->allWithPaginate(15, ['*'], ['role']);

        return Inertia::render('Sistem/User/Index', [
            'users'   => $data,
            'filters' => $request->only(['q']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Sistem/User/Form', [
            'roles' => $this->roleRepo->getForDropdown(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'nullable|exists:roles,id',
        ]);

        $this->repo->create($validated);

        return redirect()->route('sistem.user.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Sistem/User/Form', [
            'user'  => $this->repo->findById($id),
            'roles' => $this->roleRepo->getForDropdown(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $this->repo->update($id, $validated);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $this->repo->updatePassword($id, $request->password);
        }

        return redirect()->route('sistem.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function toggleActive(string $id)
    {
        $this->repo->toggleActive($id);

        return back()->with('success', 'Status user berhasil diubah.');
    }

    public function destroy(string $id)
    {
        $this->repo->delete($id);

        return redirect()->route('sistem.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
