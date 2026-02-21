<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'whatsapp_url',
        'twitter_url',
        'steam_url',
    ];

    // Relacionamento inverso
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper para verificar se tem algum link cadastrado
    public function hasAnyLink(): bool
    {
        return $this->facebook_url 
            || $this->instagram_url 
            || $this->youtube_url 
            || $this->whatsapp_url 
            || $this->twitter_url 
            || $this->steam_url;
    }
}