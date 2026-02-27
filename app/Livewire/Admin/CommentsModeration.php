<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Comment;

class CommentsModeration extends Component
{
    use WithPagination;

    public string $filterStatus = 'pending'; // pending, approved, rejected
    public string $search       = '';

    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    // ─── Ações de moderação ───────────────────────────────────────────────────

    public function approve(int $id): void
    {
        Comment::findOrFail($id)->update(['status' => 'approved']);
        $this->dispatch('notify', type: 'success', message: 'Comentário aprovado!');
    }

    public function reject(int $id): void
    {
        Comment::findOrFail($id)->update(['status' => 'rejected']);
        $this->dispatch('notify', type: 'warning', message: 'Comentário rejeitado.');
    }

    public function destroy(int $id): void
    {
        Comment::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Comentário removido.');
    }

    public function approveAll(): void
    {
        Comment::where('status', 'pending')->update(['status' => 'approved']);
        $this->dispatch('notify', type: 'success', message: 'Todos os comentários pendentes foram aprovados!');
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $comments = Comment::with(['post', 'user', 'parent'])
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn ($q) =>
                $q->where('body', 'like', '%' . $this->search . '%')
                  ->orWhere('guest_name', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(15);

        return view('livewire.admin.comments-moderation', [
            'comments'        => $comments,
            'pendingCount'    => Comment::where('status', 'pending')->count(),
            'approvedCount'   => Comment::where('status', 'approved')->count(),
            'rejectedCount'   => Comment::where('status', 'rejected')->count(),
        ]);
    }
}