<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostNotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'mute_likes',
        'mute_comments',
    ];

    protected $casts = [
        'mute_likes'    => 'boolean',
        'mute_comments' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // ─── Helpers estáticos ────────────────────────────────────────────────────

    public static function isMuted(int $userId, int $postId, string $type): bool
    {
        $setting = static::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if (! $setting) return false;

        return $type === 'likes'
            ? $setting->mute_likes
            : $setting->mute_comments;
    }

    public static function toggleMute(int $userId, int $postId, string $type): void
    {
        $setting = static::firstOrCreate(
            ['user_id' => $userId, 'post_id' => $postId]
        );

        $field = $type === 'likes' ? 'mute_likes' : 'mute_comments';
        $setting->update([$field => ! $setting->$field]);
    }
}