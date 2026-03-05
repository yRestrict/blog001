<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    use HasFactory;

    protected $table = 'sidebars';

    protected $fillable = [
        'title',
        'type',
        'status',
        'order',
        'limit',
        'period_type',
        'content',
        'link',
        'image',
        'icon',
        'color',
        'social_data',
        'fixed',
        'category_display_type',
        'selected_categories',
        'category_limit',
        'tag_display_type',
        'tag_limit',
        'selected_tags',
        'slide_images',
        'slide_interval',
        'slide_autoplay',
        'slide_controls',
        'slide_indicators',
        'image_width',
        'image_height',
    ];

    protected $casts = [
        'status' => 'boolean',
        'limit' => 'integer',
        'order' => 'integer',
        'fixed' => 'boolean',
        'social_data' => 'array',
        'selected_categories' => 'array',
        'category_limit' => 'integer',
        'tag_limit' => 'integer',
        'selected_tags' => 'array',
        'slide_images' => 'array',
        'slide_interval' => 'integer',
        'slide_autoplay' => 'boolean',
        'slide_controls' => 'boolean',
        'slide_indicators' => 'boolean',
        'image_width' => 'integer',
        'image_height' => 'integer',
    ];

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getTypeNameAttribute(): string
    {
        return [
            'search' => 'Busca',
            'categories' => 'Categorias',
            'popular_posts' => 'Posts Populares',
            'popular_downloads' => 'Downloads Populares',
            'tags' => 'Tags',
            'social_links' => 'Redes Sociais',
            'image_link' => 'Imagem com Link',
            'custom' => 'Customizado',
        ][$this->type] ?? $this->type;
    }

    public function getCategoryDisplayTypeNameAttribute(): string
    {
        return [
            'most_posts' => 'Categorias com Mais Posts',
            'most_visited' => 'Categorias Mais Visitadas',
            'manual' => 'Seleção Manual',
        ][$this->category_display_type] ?? 'Categorias com Mais Posts';
    }

    public function getTagDisplayTypeNameAttribute(): string
    {
        return [
            'most_posts' => 'Tags com Mais Posts',
            'most_visited' => 'Tags Mais Visitadas',
            'manual' => 'Seleção Manual',
        ][$this->tag_display_type] ?? 'Tags com Mais Posts';
    }

    public function getPeriodTypeNameAttribute(): string
    {
        return [
            'week' => 'Downloads da Semana',
            'month' => 'Downloads do Mês',
            'total' => 'Total de Downloads',
        ][$this->period_type] ?? 'Downloads da Semana';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
