<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\PostNotificationSetting;
use App\Models\User;
use App\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment) {}

    public function via(object $notifiable): array
    {
        if (PostNotificationSetting::isMuted($notifiable->id, $this->comment->post_id, 'comments')) {
            return [];
        }
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'comment_approved',
            'message'    => 'Comentário aprovado no post "' . $this->comment->post->title . '"',
            'author'     => $this->comment->guest_name ?? $this->comment->user?->name ?? 'Anônimo',
            'excerpt'    => Str::limit($this->comment->body, 80),
            'post_title' => $this->comment->post->title,
            'post_slug'  => $this->comment->post->slug,
            'comment_id' => $this->comment->id,
            'url'        => route('admin.comments.index'),
        ];
    }

    public static function dispatch(Comment $comment): void
    {
        User::where('role', UserRole::Owner)->get()
            ->each(fn($u) => $u->notify(new static($comment)));

        $author = User::find($comment->post->author_id);
        if ($author && ! $author->isOwner()) {
            $author->notify(new static($comment));
        }
    }
}