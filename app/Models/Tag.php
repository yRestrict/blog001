<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'views',
    ];

    protected $casts = [
        'views' => 'integer',
    ];

    // ─── Sempre salva o nome em maiúsculo ─────────────────────────────────────
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = mb_strtoupper(trim($value), 'UTF-8');
    }

    // ─── Sempre retorna o nome em maiúsculo ───────────────────────────────────
    public function getNameAttribute(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Relação muitos-para-muitos com posts via tabela pivot post_tag.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id');
    }
}