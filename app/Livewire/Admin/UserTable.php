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
    public int $perPage = 10;

    // Modal de exclusão
    public ?int $deletingUserId = null;
    public string $deletingUserName = '';

    // Reset paginação quando filtrar
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingRole(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

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

        $this->dispatch('notify', type: 'success', message: 'Status atualizado com sucesso.');
    }

    public function ban(User $user): void
    {
        $this->authorize('ban', $user);

        $newStatus = $user->isBanned() ? UserStatus::Active : UserStatus::Banned;
        $user->update(['status' => $newStatus]);

        $this->dispatch('notify', type: 'success', message: $user->isBanned() ? 'Usuário banido.' : 'Usuário desbanido.');
    }

    public function promote(User $user): void
    {
        $this->authorize('promote', User::class);
        $user->update(['role' => UserRole::Author]);

        $this->dispatch('notify', type: 'success', message: 'Usuário promovido a Autor.');
    }

    public function demote(User $user): void
    {
        $this->authorize('promote', User::class);
        $user->update(['role' => UserRole::Visitor]);

        $this->dispatch('notify', type: 'success', message: 'Cargo de Autor removido.');
    }

    public function prepareDelete(int $id): void
    {
        $user = User::findOrFail($id);
        $this->deletingUserId = $user->id;
        $this->deletingUserName = $user->name;
    }

    public function cancelDelete(): void
    {
        $this->deletingUserId = null;
        $this->deletingUserName = '';
    }

    public function deleteUser(): void
    {
        if (!$this->deletingUserId) {
            return;
        }

        $user = User::findOrFail($this->deletingUserId);
        $this->authorize('delete', $user);
        $user->delete();

        $this->dispatch('notify', type: 'success', message: 'Usuário removido com sucesso.');

        $this->cancelDelete();
    }

    public function render()
    {
        // Base query com busca (sem filtros de role/status — contamos por role)
        $baseQuery = User::query()
            ->when($this->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->status, fn($q) => $q->where('status', $this->status));

        // Widgets refletem busca + filtro de status
        $totalUsers   = (clone $baseQuery)->count();
        $ownerCount   = (clone $baseQuery)->where('role', UserRole::Owner)->count();
        $authorCount  = (clone $baseQuery)->where('role', UserRole::Author)->count();
        $visitorCount = (clone $baseQuery)->where('role', UserRole::Visitor)->count();

        // Tabela paginada (com filtro de role)
        $users = (clone $baseQuery)
            ->when($this->role, fn($q) => $q->where('role', $this->role))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.user-table', compact(
            'users',
            'totalUsers',
            'ownerCount',
            'authorCount',
            'visitorCount',
        ));
    }
}