<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\UserStatus; // Importe o seu Enum aqui

class UserController extends Controller
{
    public function index($username)
    {
        // 1. Busca o usuário com redes sociais, filtrando pelo Enum de status
        $user = User::with(['socialLinks']) 
                    ->where('username', $username)
                    ->where('status', UserStatus::Active) // Aqui você usa o Enum
                    ->first();

        if (!$user) {
            abort(404);
        }

        $data = [
            'pageTitle' => 'Posts de ' . $user->name,
            'settings'  => Setting::first(),
            'user'      => $user,
            'posts'     => $user->posts()
                                ->where('status', "published")
                                ->with(['category', 'tags']) 
                                ->latest()
                                ->paginate(6)
                                ->withQueryString()
        ];

        return view('frontend.user.index', $data);
    }
}