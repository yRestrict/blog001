<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'featured',
        'comment',
        'status',
        'meta_keywords',
        'meta_description',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'comment'  => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // ─── Relacionamentos ──────────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relação muitos-para-muitos com tags via tabela pivot post_tag.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Apenas comentários raiz aprovados (sem parent).
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->whereNull('parent_id')
                    ->where('status', 'approved')
                    ->oldest();
    }

    /**
     * Todos os comentários (para moderação no admin).
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    /**
     * Verifica se o IP atual já deu like neste post.
     */
    public function isLikedByIp(string $ip): bool
    {
        return $this->likes()->where('ip_address', $ip)->exists();
    }
}