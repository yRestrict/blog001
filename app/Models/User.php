<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\UserStatus;
use App\UserRole;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'picture',
        'bio',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'role' => UserRole::class,

        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value && file_exists(public_path('uploads/author/' . $value))) {
            return asset('uploads/author/' . $value);
        }
        
        return asset('uploads/author/default.png'); 
    }

    public function socialLinks()
    {
        return $this->hasOne(UserSocialLink::class, 'user_id');
    }

    public function isOwner(): bool
    {
        return $this->role === UserRole::Owner;
    }

    public function isAuthor(): bool
    {
        return $this->role === UserRole::Author;
    }

    public function isVisitor(): bool
    {
        return $this->role === UserRole::Visitor;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function isBanned(): bool
    {
        return $this->status === UserStatus::Banned;
    }
}
