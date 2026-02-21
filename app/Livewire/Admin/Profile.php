<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\TopUserInfo;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Profile extends Component
{
    protected $listeners = ['UpdateProfileInfo' => '$refresh'];

    public string $tab = 'personal_details';

    protected $queryString = [
        'tab' => ['keep' => true],
    ];

    // Personal Details
    public string $name;
    public string $email;
    public string $username;
    public ?string $bio = null;

    // Password Update
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public function mount()
    {
        $user = Auth::user();

        $this->fill([
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'bio' => $user->bio,
        ]);
    }

    public function selectTab(string $tab)
    {
        abort_unless(in_array($tab, [
            'personal_details',
            'update_password',
            'social_link',
        ]), 404);

        $this->tab = $tab;
    }

    public function updatePersonalDetails(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
        ]);

        $this->dispatch('showToastr', type: 'success', message: __('messages.profile_updated'));
        $this->dispatch('UpdateProfileInfo')->to(TopUserInfo::class);
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:5|confirmed',
        ]);

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', __('validation.current_password'));
            return;
        }

        if (Hash::check($this->newPassword, $user->password)) {
            $this->addError('newPassword', __('auth.new_password_same_as_current'));
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        Mail::to($user->email)->send(new PasswordChangedMail($user));

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', __('auth.password_changed_success'));
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => Auth::user(),
        ]);
    }
}