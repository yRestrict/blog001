<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public $notifications;
    public bool $open = false;

    // Polling a cada 30 segundos para verificar novas notificações
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = Auth::user();
        $this->unreadCount   = $user->unreadNotifications()->count();
        $this->notifications = $user->notifications()->latest()->take(10)->get();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;

        if ($this->open) {
            $this->loadNotifications();
        }
    }

    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function markRead(string $id): void
    {
        $notification = Auth::user()->notifications()->find($id);
        $notification?->markAsRead();
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notification-bell');
    }
}