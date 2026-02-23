<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\UserRole;
use App\UserStatus;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public string $status = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    // Reset paginação quando filtrar
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingRole(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function toggleStatus(User $user): void
    {
        $this->authorize('ban', $user);


        $newStatus = $user->isActive() ? UserStatus::Inactive : UserStatus::Active;

        $user->update(['status' => $newStatus]);
    }

    public function ban(User $user): void
    {
        $this->authorize('ban', $user);

        $newStatus = $user->isBanned() ? UserStatus::Active : UserStatus::Banned;
        $user->update(['status' => $newStatus]);
    }

    public function promote(User $user): void
    {
        $this->authorize('promote', User::class);
        $user->update(['role' => UserRole::Author]);
    }

    public function demote(User $user): void
    {
        $this->authorize('promote', User::class);
        $user->update(['role' => UserRole::Visitor]);
    }

    public function delete(User $user): void
    {
        $this->authorize('delete', $user);
        $user->delete();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->role, fn($q) => $q->where('role', $this->role))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        return view('livewire.admin.user-table', compact('users'));
    }
}