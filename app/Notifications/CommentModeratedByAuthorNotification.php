<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentModeratedByAuthorNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public string $action // 'approved' ou 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $actionLabel = $this->action === 'approved' ? 'aprovou' : 'rejeitou';

        return [
            'type'       => 'comment_moderated_by_author',
            'message'    => $this->comment->post->author->name . ' ' . $actionLabel . ' um comentário no post "' . $this->comment->post->title . '".',
            'post_title' => $this->comment->post->title,
            'post_slug'  => $this->comment->post->slug,
            'action'     => $this->action,
            'url'        => route('admin.comments.index'),
        ];
    }
}