<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function rules(?User $user = null): array
    {
        $id = $user?->id;

        return [
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($id)],
            'role' => 'required|in:admin,petugas,siswa',
            'nis' => ['nullable', 'required_if:role,siswa', 'max:30', Rule::unique('users', 'nis')->ignore($id)],
            'kelas' => 'nullable|string|max:30',
            'jurusan' => 'nullable|string|max:50',
            'tahun_lulus' => 'nullable|digits:4',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('nis', 'like', "%$s%")))
            ->when($request->role, fn ($q, $r) => $q->where('role', $r))
            ->orderBy('role')->orderBy('name')
            ->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', ['user' => new User(['role' => 'siswa', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate($this->rules($user));
        $data['is_active'] = $request->boolean('is_active');
        if (empty($data['password'])) {
            unset($data['password']);
        }
        if ($user->id === auth()->id()) {
            $data['role'] = $user->role;
            $data['is_active'] = true;
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
