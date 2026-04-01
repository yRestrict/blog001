---
key: posts-listing-refactor-design
summary: Detalhes de implementacao da refatoracao da listagem de posts para o design system mir-
---

# Design - Refatoracao da Listagem de Posts

## Arquivos Modificados
1. `resources/views/dashboard/post/index.blade.php` - Removido breadcrumb DeskApp, mantido apenas Livewire component
2. `app/Livewire/Admin/Posts.php` - Adicionado perPage, contadores, modal via properties, toggleStatus com 3 estados
3. `resources/views/livewire/admin/posts.blade.php` - Reescrita completa com design system mir-

## Estrutura do Blade
- Page Header Action: titulo + badge + botoes (Lixeira neutral, Novo Post primary)
- Widgets Grid: 4 cards (total, published, draft, private)
- Section Card: header com busca/filtro, table header, data rows, paginacao
- Modal de exclusao: controlado por $deletingPostId (Livewire), ESC via Alpine.js
- Toast container e script no @push('scripts')

## Contadores no Render
Contadores passados diretamente pelo render() como variaveis da view, nao como computed properties do Livewire.

## CSS
- Classes globais: page-header-*, widgets-grid, widget-card-*, mir-table-header, plh-*, mir-data-row, mir-pagination, mir-loading-bar, mir-status, mir-action-btn, mir-modal-*, mir-btn-*
- Classes scoped: post-section, post-section-header, post-thumb, post-body, post-name, post-info, post-meta
- Status pills para posts (is-published, is-draft, is-private) definidos no scoped style
