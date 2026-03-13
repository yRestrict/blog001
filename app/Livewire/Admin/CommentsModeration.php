<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Models\PostNotificationSetting;
use App\Notifications\CommentApprovedNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CommentsModeration extends Component
{
    use WithPagination;

    public string $filterStatus = 'pending';
    public string $search       = '';
    public bool   $showTrash    = false;

    // Para o modal de mute
    public bool   $muteModal    = false;
    public ?int   $mutePostId   = null;
    public string $mutePostTitle = '';
    public bool   $muteLikes    = false;
    public bool   $muteComments = false;

    public function updatingSearch(): void      { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingShowTrash(): void    { $this->resetPage(); }

    // ─── Moderação ────────────────────────────────────────────────────────────

    public function approve(int $id): void
    {
        $comment = Comment::findOrFail($id);

        $this->authorizeComment($comment);

        $comment->update(['status' => 'approved']);
        CommentApprovedNotification::dispatch($comment);

        $this->dispatch('notify', type: 'success', message: 'Comentário aprovado!');
    }

    public function reject(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->authorizeComment($comment);

        $comment->update(['status' => 'rejected']);
        $this->dispatch('notify', type: 'warning', message: 'Comentário rejeitado.');
    }

    public function destroy(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->authorizeComment($comment);

        $comment->delete();
        $this->dispatch('notify', type: 'success', message: 'Comentário removido.');
    }

    public function restore(int $id): void
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $this->authorizeComment($comment);

        $comment->restore();
        $this->dispatch('notify', type: 'success', message: 'Comentário restaurado.');
    }

    public function forceDelete(int $id): void
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $this->authorizeComment($comment);

        $comment->forceDelete();
        $this->dispatch('notify', type: 'success', message: 'Comentário excluído permanentemente.');
    }

    public function approveAll(): void
    {
        $query = Comment::where('status', 'pending');

        // Author só aprova dos próprios posts
        if (! Auth::user()->isOwner()) {
            $query->whereHas('post', fn($q) => $q->where('author_id', Auth::id()));
        }

        $query->get()->each(function ($comment) {
            $comment->update(['status' => 'approved']);
            CommentApprovedNotification::dispatch($comment);
        });

        $this->dispatch('notify', type: 'success', message: 'Todos os pendentes foram aprovados!');
    }

    // ─── Mute de notificações por post ───────────────────────────────────────

    public function openMuteModal(int $postId, string $postTitle): void
    {
        $this->mutePostId    = $postId;
        $this->mutePostTitle = $postTitle;

        $setting = PostNotificationSetting::where('user_id', Auth::id())
            ->where('post_id', $postId)
            ->first();

        $this->muteLikes    = $setting?->mute_likes ?? false;
        $this->muteComments = $setting?->mute_comments ?? false;
        $this->muteModal    = true;
    }

    public function saveMute(): void
    {
        PostNotificationSetting::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $this->mutePostId],
            ['mute_likes' => $this->muteLikes, 'mute_comments' => $this->muteComments]
        );

        $this->muteModal = false;
        $this->dispatch('notify', type: 'success', message: 'Preferências de notificação salvas!');
    }

    public function closeMuteModal(): void
    {
        $this->muteModal = false;
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $user    = Auth::user();
        $isOwner = $user->isOwner();

        $query = $this->showTrash
            ? Comment::onlyTrashed()->with(['post', 'user', 'parent'])
            : Comment::with(['post', 'user', 'parent'])
                ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus));

        // Author só vê comentários dos próprios posts
        if (! $isOwner) {
            $query->whereHas('post', fn($q) => $q->where('author_id', $user->id));
        }

        $query->when($this->search, fn($q) =>
            $q->where('body', 'like', '%' . $this->search . '%')
              ->orWhere('guest_name', 'like', '%' . $this->search . '%')
        )->latest();

        $comments = $query->paginate(15);

        // Contadores — author só conta dos próprios posts
        $countQuery = fn($status) => Comment::where('status', $status)
            ->when(! $isOwner, fn($q) =>
                $q->whereHas('post', fn($q2) => $q2->where('author_id', $user->id))
            )->count();

        $trashCount = Comment::onlyTrashed()
            ->when(! $isOwner, fn($q) =>
                $q->whereHas('post', fn($q2) => $q2->where('author_id', $user->id))
            )->count();

        return view('livewire.admin.comments-moderation', [
            'comments'      => $comments,
            'pendingCount'  => $countQuery('pending'),
            'approvedCount' => $countQuery('approved'),
            'rejectedCount' => $countQuery('rejected'),
            'trashCount'    => $trashCount,
            'isOwner'       => $isOwner,
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeComment(Comment $comment): void
    {
        $user = Auth::user();

        if ($user->isOwner()) return;

        // Author só pode moderar comentários dos próprios posts
        if ($comment->post->author_id !== $user->id) {
            abort(403);
        }
    }
}