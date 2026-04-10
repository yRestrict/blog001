<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TopUserInfo extends Component
{
    protected $listeners = ['UpdateProfileInfo' => '$refresh'];
    public function render()
    {
        $user = User::findOrFail(Auth::id());

        return view('livewire.admin.top-user-info', [
            'user'    => $user,
            'isOwner' => $user->isOwner(),
        ]);
    }  
}