<?php

namespace App\Notifications;

use App\Models\PostLike;
use App\Models\PostNotificationSetting;
use App\Models\User;
use App\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReactionNotification extends Notification
{
    use Queueable;

    public function __construct(public PostLike $like) {}

    public function via(object $notifiable): array
    {
        if (PostNotificationSetting::isMuted($notifiable->id, $this->like->post_id, 'likes')) {
            return [];
        }
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $emoji = $this->like->type === 'like' ? '😍' : '😢';
        $label = $this->like->type === 'like' ? 'curtiu' : 'não curtiu';

        return [
            'type'       => 'reaction',
            'message'    => "Alguém {$label} o post \"{$this->like->post->title}\"",
            'emoji'      => $emoji,
            'reaction'   => $this->like->type,
            'post_title' => $this->like->post->title,
            'post_slug'  => $this->like->post->slug,
            'url'        => route('frontend.post', $this->like->post->slug),
        ];
    }

    /**
     * Dispara para owners + author do post, respeitando mute.
     */
    public static function dispatch(PostLike $like): void
    {
        User::where('role', UserRole::Owner)->get()
            ->each(fn($u) => $u->notify(new static($like)));

        $author = User::find($like->post->author_id);
        if ($author && ! $author->isOwner()) {
            $author->notify(new static($like));
        }
    }
}