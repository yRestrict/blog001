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
        $isReply    = (bool) $this->comment->parent_id;
        $isFromTeam = $this->comment->user_id !== null;
        $isOwnPost  = $isFromTeam && $this->comment->user_id === $this->comment->post->author_id;

        if ($isReply) {
            $replierName = $this->comment->user?->name ?? $this->comment->guest_name ?? 'Alguém';
            $message = '"' . $replierName . '" respondeu a um comentário no post "' . $this->comment->post->title . '"';
        } elseif ($isOwnPost) {
            $message = 'O autor "' . $this->comment->user->name . '" comentou no próprio post "' . $this->comment->post->title . '"';
        } elseif ($isFromTeam) {
            $message = 'O colega "' . $this->comment->user->name . '" comentou no post "' . $this->comment->post->title . '"';
        } else {
            $message = 'Novo comentário no post "' . $this->comment->post->title . '"';
        }

        return [
            'type'       => $isReply ? 'reply' : 'comment',
            'message'    => $message,
            'author'     => $this->comment->guest_name ?? $this->comment->user?->name ?? 'Anônimo',
            'excerpt'    => Str::limit($this->comment->body, 80),
            'post_title' => $this->comment->post->title,
            'post_slug'  => $this->comment->post->slug,
            'comment_id' => $this->comment->id,
            'url'        => route('admin.comments.index'),
        ];
    }

    /**
     * Regras de disparo:
     *
     * Comentário raiz (visitante ou colega):
     *   → Owner + Author do post recebem (exceto a si mesmo)
     *
     * Reply de visitante:
     *   → Owner + Author do post recebem (eles precisam moderar)
     *
     * Reply entre colegas:
     *   → Apenas Owner recebe (Author do post NÃO recebe aqui —
     *     ele só recebe via CommentRepliedNotification se for ele
     *     quem foi respondido diretamente)
     */
    public static function dispatch(Comment $comment): void
    {
        $postAuthorId  = $comment->post->author_id;
        $commentUserId = $comment->user_id; // null = guest
        $isReply       = (bool) $comment->parent_id;
        $isTeamReply   = $isReply && $commentUserId !== null;

        // Owners sempre recebem (exceto a si mesmos)
        User::where('role', UserRole::Owner)->get()
            ->each(function ($owner) use ($comment, $commentUserId) {
                if ($owner->id === $commentUserId) return; // anti-spam
                $owner->notify(new static($comment));
            });

        // Author do post recebe APENAS se:
        //  - Não for owner
        //  - Não for a si mesmo comentando
        //  - NÃO for um reply entre colegas (nesses casos só recebe via CommentRepliedNotification)
        if ($isTeamReply) return;

        $author = User::find($postAuthorId);

        if ($author && ! $author->isOwner() && $author->id !== $commentUserId) {
            $author->notify(new static($comment));
        }
    }
}