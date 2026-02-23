<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'type',
        'parent_id',
        'title',
        'url',
        'target',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Todos os filhos (sem filtro de is_active).
     * Usado no painel admin para exibir/editar todos os itens.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('order');
    }

    /**
     * Apenas filhos ativos, com seus filhos ativos recursivamente.
     * Usado nos componentes Header e Footer do frontend.
     */
    public function activeChildren()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->with(['activeChildren']);
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function scopeHeader($query)
    {
        return $query->where('type', 'header');
    }

    public function scopeFooter($query)
    {
        return $query->where('type', 'footer');
    }

    public static function limiteRaizAtingido($type, $ignoreId = null)
    {
        $query = self::where('type', $type)
            ->whereNull('parent_id');

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->count() >= 7;
    }

    public static function limiteFilhoAtingido(int $parentId, ?int $ignoreId = null, int $limite = 5): bool
    {
        $query = self::where('parent_id', $parentId);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->count() >= $limite;
    }

    public function deactivateDescendants(): void
    {
        foreach ($this->children as $child) {
            if ($child->is_active) {
                $child->update(['is_active' => false]);
            }

            $child->deactivateDescendants();
        }
    }

    protected static function booted()
    {
        static::updating(function (Menu $menu) {

            // Se está sendo desativado
            if ($menu->isDirty('is_active') && $menu->is_active === false) {
                $menu->load('children.children.children');
                $menu->deactivateDescendants();
            }
        });
    }

    
}