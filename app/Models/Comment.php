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
     * Comentário pai (quando é um reply).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Respostas deste comentário.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'approved')->oldest();
    }

    /**
     * Todas as respostas (inclusive pendentes — para moderação).
     */
    public function allReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Retorna o nome do autor (usuário logado ou visitante).
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Anônimo';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}