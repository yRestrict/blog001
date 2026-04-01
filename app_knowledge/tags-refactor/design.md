---
key: tags-refactor-design
summary: Detalhes de implementação da refatoração de tags com design system mir-
---

# Design - Refatoração Tags

## Arquivos Modificados
1. `resources/views/dashboard/tag/index.blade.php` - Simplificado, sem breadcrumb
2. `app/Livewire/Admin/Tags.php` - Adicionado perPage, renomeados métodos para padrão modal
3. `resources/views/livewire/admin/tags.blade.php` - Reescrita completa com mir-

## Componentes Utilizados (classes globais do custom.css)
- `.page-header-action` + `.page-header-title-count` - Header com badge de contagem
- `.mir-section` + `.mir-section-header` - Section card genérica
- `.section-icon-header` + `.section-icon-indigo` - Ícone no header da seção
- `.mir-table-header` + `.plh-body` / `.plh-divider` / `.plh-actions` - Cabeçalho de colunas
- `.mir-data-list` + `.mir-data-row` - Rows flexbox
- `.mir-badge-count` - Badge de contagem de posts
- `.mir-divider` - Divisor vertical
- `.mir-action-btn` + `.mir-action-edit` / `.mir-action-delete` - Botões de ação 30x30
- `.mir-pagination` com 3 zonas + `.mir-per-page` - Paginação completa
- `.mir-loading-bar` + `.mir-loading-overlay` - Estado de carregamento
- `.mir-empty-state` - Estado vazio
- `.mir-modal-*` - Modais de criar/editar/excluir
- `.mir-toast` - Toasts de feedback

## Colunas da Data Row
| Coluna | Largura |
|--------|---------|
| Tag (nome + slug) | flex: 1 1 0 |
| Posts (badge) | 100px fixo |
| Divider | 1px |
| Ações | 68px (plh-actions) |

## Métodos Livewire
- `openCreateModal()` / `openEditModal(int $id)` / `closeModal()` / `saveTag()`
- `prepareDelete(int $id)` / `cancelDelete()` / `deleteTag()`
- `updatingPerPage()` reseta a página
