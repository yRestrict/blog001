<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
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
    public ?int   $replyingTo      = null;
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
            $this->validate([
                'body' => 'required|string|min:3|max:2000',
            ]);
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

        if ($status === 'pending') {
            NewCommentNotification::dispatch($comment);
        }

        $this->reset('body');
        $this->submitted = true;
    }

    public function startReply(int $commentId, string $authorName): void
    {
        $this->replyingTo      = $commentId;
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
            $this->validate([
                'replyBody' => 'required|string|min:3|max:2000',
            ]);
        } else {
            $this->validate([
                'replyGuestName' => 'required|string|max:100',
                'replyBody'      => 'required|string|min:3|max:2000',
            ]);
        }

        $parent   = Comment::findOrFail($this->replyingTo);
        $parentId = $parent->parent_id ?? $parent->id;
        $status   = $this->resolveStatus();

        $reply = $this->post->comments()->create([
            'user_id'     => Auth::id(),
            'parent_id'   => $parentId,
            'guest_name'  => Auth::check() ? null : $this->replyGuestName,
            'guest_email' => Auth::check() ? null : $this->email,
            'body'        => $this->replyBody,
            'status'      => $status,
            'ip_address'  => request()->ip(),
        ]);

        if ($status === 'pending') {
            NewCommentNotification::dispatch($reply);
        }

        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->replySubmitted = true;
        $this->submitted      = false;
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->with(['user', 'replies.user'])
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

        // Owner e Author vão direto como aprovado em qualquer post
        if ($user->isOwner() || $user->isAuthor()) return 'approved';

        return 'pending';
    }
}