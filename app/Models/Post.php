<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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
        'thumbnail',
        'views',       
        'featured',
        'comment',
        'status',
        'meta_keywords',
        'meta_description',
        'downloads',
    ];

    protected $casts = [
        'featured'  => 'boolean',
        'comment'   => 'boolean',
        'views'     => 'integer',
        'downloads' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // ─── Atributos Virtuais (Accessors) ───────────────────────────────────────

    public function getCleanDescriptionAttribute()
    {
        $html = $this->content;

        $htmlSemCodigo = preg_replace('/<pre\b[^>]*>(.*?)<\/pre>/is', '', $html);

        $textoPuro = strip_tags($htmlSemCodigo);

        $textoLimpo = trim(preg_replace('/\s+/', ' ', $textoPuro));

        return Str::limit($textoLimpo, 150);
    }

    // ─── Relacionamentos ──────────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')
                    ->withDefault([
                        'name' => 'Sem Categoria',
                        'slug' => 'sem-categoria',
                    ]);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->whereNull('parent_id')
                    ->where('status', 'approved')
                    ->oldest();
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function isLikedByIp(string $ip): bool
    {
        return $this->likes()->where('ip_address', $ip)->exists();
    }

    public function downloadButtons()
    {
        return $this->hasMany(PostDownload::class)->orderBy('order');
    }

    public function hasDownloads(): bool
    {
        return $this->downloadButtons()->exists();
    }
}