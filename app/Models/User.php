<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\UserStatus;
use App\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'username', 'picture', 'bio',
        'role', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status'            => UserStatus::class,
            'role'              => UserRole::class,
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value && file_exists(public_path('uploads/author/' . $value))) {
            return asset('uploads/author/' . $value);
        }
        return asset('uploads/author/default.png');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function socialLinks()
    {
        return $this->hasOne(UserSocialLink::class, 'user_id');
    }

    public function notificationSettings()
    {
        return $this->hasMany(PostNotificationSetting::class);
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function isOwner(): bool   { return $this->role === UserRole::Owner; }
    public function isAuthor(): bool  { return $this->role === UserRole::Author; }
    public function isVisitor(): bool { return $this->role === UserRole::Visitor; }
    public function isActive(): bool  { return $this->status === UserStatus::Active; }
    public function isBanned(): bool  { return $this->status === UserStatus::Banned; }

    public function hasMutedPost(int $postId, string $type): bool
    {
        return PostNotificationSetting::isMuted($this->id, $postId, $type);
    }

    public function autoApprovePosts(): bool
    {
        return $this->settings?->auto_approve_posts ?? false;
    }
}