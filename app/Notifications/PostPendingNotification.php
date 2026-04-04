<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostPendingNotification extends Notification
{
    use Queueable;

    public function __construct(public Post $post) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'post_pending',
            'message'     => 'O post "' . $this->post->title . '" de ' . $this->post->author->name . ' aguarda sua aprovação.',
            'post_title'  => $this->post->title,
            'post_slug'   => $this->post->slug,
            'author_name' => $this->post->author->name,
            'url'         => route('admin.posts.pending'),
        ];
    }
}