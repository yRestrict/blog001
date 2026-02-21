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
        return view('livewire.admin.top-user-info', [
            'user' => User::findOrFail(Auth::id()),
        ]);
    }  
}
