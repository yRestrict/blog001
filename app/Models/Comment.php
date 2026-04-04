<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'reply_to_id',  // comentário específico respondido (dentro da thread)
        'guest_name',
        'guest_email',
        'body',
        'status',
        'ip_address',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────────

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comentário raiz da thread (quando é um reply).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Comentário específico que foi respondido (pode ser um reply de reply).
     * Usado para notificações e para exibir "@Nome" na UI.
     */
    public function replyTo()
    {
        return $this->belongsTo(Comment::class, 'reply_to_id');
    }

    /**
     * Respostas aprovadas deste comentário (para exibição no frontend).
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->where('status', 'approved')
                    ->oldest();
    }

    /**
     * Todas as respostas inclusive pendentes (para moderação).
     */
    public function allReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Anônimo';
    }

    /**
     * Nome de quem este comentário está respondendo (para exibir "@Nome" na UI).
     */
    public function getReplyToNameAttribute(): ?string
    {
        if (! $this->reply_to_id) return null;

        return $this->replyTo?->user?->name
            ?? $this->replyTo?->guest_name
            ?? null;
    }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
}