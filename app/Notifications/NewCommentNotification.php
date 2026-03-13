<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\PostNotificationSetting;
use App\Models\User;
use App\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification
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
        $isReply = (bool) $this->comment->parent_id;

        return [
            'type'       => $isReply ? 'reply' : 'comment',
            'message'    => $isReply
                ? 'Nova resposta no post "' . $this->comment->post->title . '"'
                : 'Novo comentário no post "' . $this->comment->post->title . '"',
            'author'     => $this->comment->guest_name ?? $this->comment->user?->name ?? 'Anônimo',
            'excerpt'    => Str::limit($this->comment->body, 80),
            'post_title' => $this->comment->post->title,
            'post_slug'  => $this->comment->post->slug,
            'comment_id' => $this->comment->id,
            'url'        => route('admin.comments.index'),
        ];
    }

    /**
     * Dispara para owners + author do post, respeitando mute.
     */
    public static function dispatch(Comment $comment): void
    {
        // Todos os owners
        User::where('role', UserRole::Owner)->get()
            ->each(fn($u) => $u->notify(new static($comment)));

        // Author do post (se não for owner e não for quem comentou)
        $author = User::find($comment->post->author_id);
        if ($author && ! $author->isOwner() && $author->id !== $comment->user_id) {
            $author->notify(new static($comment));
        }
    }
}