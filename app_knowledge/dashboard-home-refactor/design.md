---
key: dashboard-home-refactor-design
summary: Detalhes de implementacao da refatoracao da pagina Dashboard home
---

# Design - Dashboard Home Refactor

## Arquivos modificados
- `resources/views/dashboard/home/index.blade.php` - Simplificado, removido breadcrumb
- `app/Livewire/Admin/Dashboard.php` - Sem alteracoes (ja passava todos os dados necessarios)
- `resources/views/livewire/admin/dashboard.blade.php` - Reescrito completamente

## Estrutura da view
1. Page Header Action (sem botoes, apenas titulo + subtitulo com saudacao)
2. Widgets grid Posts: total, publicados, rascunhos, privados
3. Widgets grid Outros: tags, categorias, likes, comentarios pendentes
4. Atalhos rapidos: grid 4 colunas com .quick-action (classe global) + .dash-shortcuts-grid (scoped)
5. Section Card Ultimos Posts: mir-section + mir-table-header + mir-data-row (sem paginacao, top 8)

## Classes globais reutilizadas
- `.page-header-action`, `.page-header-left`, `.page-header-title`, `.page-header-sub`
- `.widgets-grid`, `.widget-card`, `.widget-card-*`, `.widget-info`, `.widget-value`, `.widget-label`, `.widget-icon`, `.widget-icon-*`
- `.quick-action` (border-radius 14px, hover translateY)
- `.mir-section`, `.mir-section-header`, `.mir-section-title`, `.mir-section-sub`, `.mir-section-header-right`
- `.section-icon-header`, `.section-icon`, `.section-icon-indigo`
- `.mir-table-header`, `.plh-thumb`, `.plh-body`, `.plh-divider`, `.plh-status`
- `.mir-data-row`, `.mir-data-list`
- `.post-thumb`, `.post-body`, `.post-name`, `.post-info`, `.post-info-dot`
- `.mir-divider`, `.mir-status`, `.mir-status-ring`, `.is-published`, `.is-draft`, `.is-private`
- `.mir-badge-feat`
- `.mir-empty-state`, `.mir-empty-icon`, `.mir-empty-title`, `.mir-empty-desc`
- `.mir-btn-neutral`, `.mir-btn-primary-lg`

## Classes scoped (dash-)
- `.dash-shortcuts-grid` - Grid 4 colunas para atalhos rapidos
- `.dash-shortcut-icon` - Icone circular com fundo semi-transparente
- `.dash-shortcut-label` - Label do atalho
- `.dash-post-date` - Data na coluna de ultimos posts
