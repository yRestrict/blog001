<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Post $post,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = 'Seu post "' . $this->post->title . '" foi rejeitado.';

        if ($this->reason) {
            $message .= ' Motivo: ' . $this->reason;
        }

        return [
            'type'       => 'post_rejected',
            'message'    => $message,
            'post_title' => $this->post->title,
            'post_slug'  => $this->post->slug,
            'reason'     => $this->reason,
            'url'        => route('admin.posts.edit', $this->post),
        ];
    }
}