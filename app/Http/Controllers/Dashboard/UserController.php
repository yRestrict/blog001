<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return view('dashboard.user.index', [
            'pageTitle' => 'Usuários',
        ]);
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
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users',
            'username'           => 'required|string|unique:users',
            'password'           => 'required|min:8|confirmed',
            'role'               => 'required|in:owner,author,visitor',
            'auto_approve_posts' => 'nullable|boolean',
        ]);

        $user = User::create($data);

        // Cria as settings com auto_approve
        UserSetting::create([
            'user_id'            => $user->id,
            'auto_approve_posts' => $request->boolean('auto_approve_posts'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado!');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('dashboard.user.edit', [
            'user'         => $user->load('settings'),
            'pageTitle'    => 'Editar Usuário',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email,' . $user->id,
            'username'           => 'required|string|unique:users,username,' . $user->id,
            'bio'                => 'nullable|string',
            'role'               => 'sometimes|in:owner,author,visitor',
            'auto_approve_posts' => 'nullable|boolean',
        ]);

        $user->update($data);

        // Atualiza ou cria as settings
        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            ['auto_approve_posts' => $request->boolean('auto_approve_posts')]
        );

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado!');
    }

    // ─── Toggle rápido na listagem ────────────────────────────────────────────

    public function toggleAutoApprove(User $user)
    {
        abort_unless(Auth::user()->isOwner(), 403);

        $current = $user->settings?->auto_approve_posts ?? false;

        UserSetting::updateOrCreate(
            ['user_id' => $user->id],
            ['auto_approve_posts' => ! $current]
        );

        $label = ! $current ? 'ativado' : 'desativado';

        return redirect()->back()->with('success', "Auto-aprovação {$label} para {$user->name}.");
    }
}