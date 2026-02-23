<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $data = [
            'pageTitle' => 'Usuários',
        ];
        return view('dashboard.user.index', $data); 
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('dashboard.user.create');
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

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado!');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('dashboard.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string',
            'role'     => 'sometimes|in:owner,author,visitor',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado!');
    }
}