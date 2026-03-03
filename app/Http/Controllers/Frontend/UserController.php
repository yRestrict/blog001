<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index($username)
    {
        // Buscar usuário pelo username
        $user = User::where('username', $username)->firstOrFail();

        // Buscar posts desse usuário
        $recentposts = $user->posts()
            ->latest()
            ->paginate(10);

        return view('frontend.user.index', [
            'pageTitle'   => "Posts de {$user->name}",
            'user'        => $user,
            'recentposts' => $recentposts,
        ]);
    }
}
