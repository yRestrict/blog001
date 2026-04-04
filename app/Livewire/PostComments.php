<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentRepliedNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostComments extends Component
{
    public Post $post;

    // ─── Comentário principal ─────────────────────────────────────────────────
    public string $name      = '';
    public string $email     = '';
    public string $body      = '';
    public bool   $submitted = false;

    // ─── Reply ───────────────────────────────────────────────────────────────
    public ?int   $replyingTo      = null; // ID do comentário clicado (pode ser reply)
    public string $replyAuthorName = '';
    public string $replyBody       = '';
    public string $replyGuestName  = '';
    public bool   $replySubmitted  = false;

    public function mount(Post $post): void
    {
        $this->post = $post;

        if (Auth::check()) {
            $this->name  = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    public function submit(): void
    {
        if (Auth::check()) {
            $this->validate(['body' => 'required|string|min:3|max:2000']);
            $this->name = Auth::user()->name;
        } else {
            $this->validate([
                'name'  => 'required|string|max:100',
                'email' => 'nullable|email|max:255',
                'body'  => 'required|string|min:3|max:2000',
            ]);
        }

        $status  = $this->resolveStatus();
        $comment = $this->post->comments()->create([
            'user_id'     => Auth::id(),
            'guest_name'  => Auth::check() ? null : $this->name,
            'guest_email' => Auth::check() ? null : $this->email,
            'body'        => $this->body,
            'status'      => $status,
            'ip_address'  => request()->ip(),
        ]);

        NewCommentNotification::dispatch($comment);

        $this->reset('body');
        $this->submitted = true;
    }

    public function startReply(int $commentId, string $authorName): void
    {
        $this->replyingTo      = $commentId; // ID do comentário específico clicado
        $this->replyAuthorName = $authorName;
        $this->replyBody       = '';
        $this->replyGuestName  = '';
        $this->replySubmitted  = false;
    }

    public function cancelReply(): void
    {
        $this->replyingTo     = null;
        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->replySubmitted = false;
    }

    public function submitReply(): void
    {
        if (Auth::check()) {
            $this->validate(['replyBody' => 'required|string|min:3|max:2000']);
        } else {
            $this->validate([
                'replyGuestName' => 'required|string|max:100',
                'replyBody'      => 'required|string|min:3|max:2000',
            ]);
        }

        // Comentário clicado — pode ser raiz ou um reply
        $clicked = Comment::findOrFail($this->replyingTo);

        // parent_id → sempre a raiz da thread (para agrupar)
        $parentId = $clicked->parent_id ?? $clicked->id;

        // reply_to_id → o comentário exato que foi respondido (para notificação e "@Nome")
        $replyToId = $clicked->id;

        $status = $this->resolveStatus();

        $reply = $this->post->comments()->create([
            'user_id'     => Auth::id(),
            'parent_id'   => $parentId,
            'reply_to_id' => $replyToId,
            'guest_name'  => Auth::check() ? null : $this->replyGuestName,
            'guest_email' => Auth::check() ? null : $this->email,
            'body'        => $this->replyBody,
            'status'      => $status,
            'ip_address'  => request()->ip(),
        ]);

        // 1. Notifica owners + author do post sobre o novo reply
        NewCommentNotification::dispatch($reply);

        // 2. Notifica quem foi respondido especificamente (se for diferente do author do post)
        CommentRepliedNotification::dispatch($reply);

        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->replySubmitted = true;
        $this->submitted      = false;
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->with(['user', 'replies.user', 'replies.replyTo.user'])
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('livewire.post-comments', compact('comments'));
    }

    private function resolveStatus(): string
    {
        if (! Auth::check()) return 'pending';

        $user = Auth::user();

        if ($user->isOwner() || $user->isAuthor()) return 'approved';

        return 'pending';
    }
}