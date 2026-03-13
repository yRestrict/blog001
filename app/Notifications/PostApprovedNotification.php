<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostApprovedNotification extends Notification
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
            'type'       => 'post_approved',
            'message'    => 'Seu post "' . $this->post->title . '" foi aprovado e está publicado!',
            'post_title' => $this->post->title,
            'post_slug'  => $this->post->slug,
            'url'        => route('frontend.post', $this->post->slug),
        ];
    }
}