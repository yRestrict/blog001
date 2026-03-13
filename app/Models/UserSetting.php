<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'auto_approve_posts',
    ];

    protected $casts = [
        'auto_approve_posts' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}