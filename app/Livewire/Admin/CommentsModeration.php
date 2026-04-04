<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Models\PostNotificationSetting;
use App\Notifications\CommentApprovedNotification;
use App\Notifications\CommentModeratedByAuthorNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CommentsModeration extends Component
{
    use WithPagination;

    public string $filterStatus  = 'pending';
    public string $search        = '';
    public bool   $showTrash     = false;
    public int    $perPage       = 10;

    // Modal de exclusão
    public ?int   $deletingCommentId   = null;
    public string $deletingCommentBody = '';

    // Modal de mute
    public bool   $muteModal     = false;
    public ?int   $mutePostId    = null;
    public string $mutePostTitle = '';
    public bool   $muteLikes     = false;
    public bool   $muteComments  = false;

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingShowTrash(): void    { $this->resetPage(); }
    public function updatingPerPage(): void      { $this->resetPage(); }

    // ─── Moderação ────────────────────────────────────────────────────────────

    public function approve(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->authorizeComment($comment);

        $comment->update(['status' => 'approved']);
        CommentApprovedNotification::dispatch($comment);

        if (Auth::user()->isAuthor()) {
            $this->notifyOwners($comment, 'approved');
        }

        $this->dispatch('notify', type: 'success', message: 'Comentário aprovado!');
    }

    public function reject(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->authorizeComment($comment);

        $comment->update(['status' => 'rejected']);

        if (Auth::user()->isAuthor()) {
            $this->notifyOwners($comment, 'rejected');
        }

        $this->dispatch('notify', type: 'warning', message: 'Comentário rejeitado.');
    }

    public function prepareDelete(int $id): void
    {
        if (! Auth::user()->isOwner()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para excluir comentários.');
            return;
        }

        $comment = Comment::findOrFail($id);
        $this->deletingCommentId   = $comment->id;
        $this->deletingCommentBody = Str::limit($comment->body, 80);
    }

    public function cancelDelete(): void
    {
        $this->deletingCommentId   = null;
        $this->deletingCommentBody = '';
    }

    public function destroy(): void
    {
        if (! Auth::user()->isOwner()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para excluir comentários.');
            return;
        }

        $comment = Comment::findOrFail($this->deletingCommentId);
        $comment->delete();

        $this->deletingCommentId   = null;
        $this->deletingCommentBody = '';
        $this->dispatch('notify', type: 'success', message: 'Comentário removido.');
    }

    public function restore(int $id): void
    {
        if (! Auth::user()->isOwner()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para restaurar comentários.');
            return;
        }

        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();
        $this->dispatch('notify', type: 'success', message: 'Comentário restaurado.');
    }

    public function forceDelete(int $id): void
    {
        if (! Auth::user()->isOwner()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para excluir permanentemente.');
            return;
        }

        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();
        $this->dispatch('notify', type: 'success', message: 'Comentário excluído permanentemente.');
    }

    public function approveAll(): void
    {
        $user    = Auth::user();
        $isOwner = $user->isOwner();

        $query = Comment::where('status', 'pending');

        if (! $isOwner) {
            $query->whereHas('post', fn($q) => $q->where('author_id', Auth::id()));
        }

        $query->get()->each(function ($comment) use ($isOwner) {
            $comment->update(['status' => 'approved']);
            CommentApprovedNotification::dispatch($comment);

            if (! $isOwner) {
                $this->notifyOwners($comment, 'approved');
            }
        });

        $this->dispatch('notify', type: 'success', message: 'Todos os pendentes foram aprovados!');
    }

    // ─── Mute ────────────────────────────────────────────────────────────────

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

        // IDs de posts que o usuário silenciou (para indicadores na view)
        $mutedPostIds = PostNotificationSetting::where('user_id', $user->id)
            ->where(fn($q) => $q->where('mute_likes', true)->orWhere('mute_comments', true))
            ->pluck('post_id')
            ->toArray();

        // ── Query base ──────────────────────────────────────────────────────
        $query = $this->showTrash
            ? Comment::onlyTrashed()->with(['post', 'user', 'parent'])
            : Comment::with(['post', 'user', 'parent'])
                ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus));

        // Author só vê comentários dos próprios posts
        if (! $isOwner) {
            $query->whereHas('post', fn($q) => $q->where('author_id', $user->id));
        }

        // Busca agrupada — sem esse agrupamento o orWhere vaza o filtro de author
        $query->when($this->search, fn($q) =>
            $q->where(fn($sub) =>
                $sub->where('body', 'like', '%' . $this->search . '%')
                    ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                    ->orWhere('guest_email', 'like', '%' . $this->search . '%')
            )
        );

        $comments = $query->latest()->paginate($this->perPage);

        // ── Contadores ──────────────────────────────────────────────────────
        $countBase = fn() => Comment::when(! $isOwner, fn($q) =>
            $q->whereHas('post', fn($q2) => $q2->where('author_id', $user->id))
        );

        $pendingCount  = (clone $countBase())->where('status', 'pending')->count();
        $approvedCount = (clone $countBase())->where('status', 'approved')->count();
        $rejectedCount = (clone $countBase())->where('status', 'rejected')->count();
        $totalCount    = $pendingCount + $approvedCount + $rejectedCount;

        $trashCount = Comment::onlyTrashed()
            ->when(! $isOwner, fn($q) =>
                $q->whereHas('post', fn($q2) => $q2->where('author_id', $user->id))
            )->count();

        return view('livewire.admin.comments-moderation', [
            'comments'      => $comments,
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'trashCount'    => $trashCount,
            'totalCount'    => $totalCount,
            'isOwner'       => $isOwner,
            'mutedPostIds'  => $mutedPostIds,
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeComment(Comment $comment): void
    {
        $user = Auth::user();

        if ($user->isOwner()) return;

        if ($comment->post->author_id !== $user->id) {
            abort(403);
        }
    }

    private function notifyOwners(Comment $comment, string $action): void
    {
        User::where('role', 'owner')->get()
            ->each(fn($owner) => $owner->notify(
                new CommentModeratedByAuthorNotification($comment, $action)
            ));
    }
}