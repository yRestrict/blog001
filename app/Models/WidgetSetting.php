<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MODEL: WidgetSetting
 *
 * Representa uma única configuração de um widget.
 * Este model é simples — a lógica de leitura/escrita está no SidebarWidget
 * através dos métodos getSetting() e setSetting().
 *
 * Estrutura de uma linha:
 *   widget_id = 1
 *   key       = 'limit'
 *   value     = '8'        (sempre string no banco)
 *   type      = 'integer'  (informa como fazer o cast na leitura)
 */
class WidgetSetting extends Model
{
    protected $table = 'widget_settings';

    protected $fillable = [
        'widget_id',
        'key',
        'value',
        'type',
    ];

    // =========================================================================
    // RELACIONAMENTOS
    // =========================================================================

    /**
     * O widget ao qual esta configuração pertence.
     * Uso: $setting->widget → retorna instância de SidebarWidget
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(SidebarWidget::class, 'widget_id');
    }
}