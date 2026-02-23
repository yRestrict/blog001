<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\UserRole;
use App\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::latest()->paginate(20);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:owner,author,visitor',
        ]);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuário criado!');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string',
        ]);

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido!');
    }

    public function ban(User $user)
    {
        $this->authorize('ban', $user);

        $user->update(['status' => UserStatus::Banned]);

        return back()->with('success', 'Usuário banido!');
    }

    public function promote(User $user)
    {
        $this->authorize('promote', User::class);

        $user->update(['role' => UserRole::Author]);

        return back()->with('success', 'Usuário promovido a autor!');
    }
}
