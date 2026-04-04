<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\PostNotificationSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentRepliedNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $reply) {}

    public function via(object $notifiable): array
    {
        if (PostNotificationSetting::isMuted($notifiable->id, $this->reply->post_id, 'comments')) {
            return [];
        }
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $replierName = $this->reply->user?->name ?? $this->reply->guest_name ?? 'Alguém';
        $postTitle   = $this->reply->post->title;

        // reply_to_id aponta para o comentário exato respondido.
        // Se esse comentário também tem parent_id, é um reply de reply.
        $replyTo       = $this->reply->replyTo; // relacionamento do model
        $isReplyOfReply = $replyTo && $replyTo->parent_id !== null;

        $message = $isReplyOfReply
            ? '"' . $replierName . '" também respondeu na conversa do post "' . $postTitle . '"'
            : '"' . $replierName . '" respondeu ao seu comentário no post "' . $postTitle . '"';

        return [
            'type'       => 'comment_replied',
            'message'    => $message,
            'author'     => $replierName,
            'excerpt'    => Str::limit($this->reply->body, 80),
            'post_title' => $postTitle,
            'post_slug'  => $this->reply->post->slug,
            'comment_id' => $this->reply->id,
            'url'        => route('admin.comments.index'),
        ];
    }

    public static function dispatch(Comment $reply): void
    {
        $replyToId = $reply->reply_to_id;

        if (! $replyToId) return;

        $replyTo = Comment::with('user')->find($replyToId);

        if (! $replyTo || ! $replyTo->user_id) return; // guest, sem conta

        if ($replyTo->user_id === $reply->user_id) return; // anti-spam (a si mesmo)

        // Se quem foi respondido é o author do post, ele JÁ recebe via
        // NewCommentNotification (comentários raiz/visitantes).
        // Para replies entre colegas, NewCommentNotification foi ajustado
        // para NÃO notificar o author — então aqui sim, ele deve receber.
        $replyTo->user->notify(new static($reply));
    }
}