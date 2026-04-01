---
key: users-listing-refactor-design
summary: Detalhes de implementacao da refatoracao da listagem de usuarios para o design system mir-
---

# Design - Refatoracao da Listagem de Usuarios

## Arquivos modificados
- `resources/views/dashboard/user/index.blade.php` - Simplificado, removido breadcrumb DeskApp
- `app/Livewire/Admin/UserTable.php` - Adicionado perPage, modal de exclusao, contadores por role
- `resources/views/livewire/admin/user-table.blade.php` - Reescrito completo com design system mir-

## Componentes globais reutilizados (de custom.css)
- `.page-header-action`, `.page-header-left`, `.page-header-right`, `.page-header-title`, `.page-header-title-count`, `.page-header-sub`
- `.widgets-grid`, `.widget-card`, `.widget-info`, `.widget-label`, `.widget-value`, `.widget-icon`, `.widget-card-total`, `.widget-icon-total`
- `.mir-table-header`, `.plh-divider`, `.plh-actions`
- `.mir-data-list`, `.mir-data-row`
- `.mir-status`, `.is-active`, `.is-inactive`, `.mir-status-ring`
- `.mir-divider`, `.mir-actions`, `.mir-action-btn`, `.mir-action-edit`, `.mir-action-delete`
- `.mir-pagination`, `.mir-pagination-left`, `.mir-pagination-center`, `.mir-pagination-right`, `.mir-page-btn`, `.mir-per-page`
- `.mir-loading-bar`, `.mir-loading-overlay`
- `.mir-modal-overlay`, `.mir-modal-dialog`, `.mir-modal-content`, `.mir-modal-header`, `.mir-modal-body`, `.mir-modal-footer`
- `.mir-btn-primary-lg`, `.mir-btn-neutral`, `.mir-btn-danger`, `.mir-btn-ghost`
- `.mir-empty-state`, `.mir-empty-icon`, `.mir-empty-title`, `.mir-empty-desc`
- `.mir-input`, `.mir-toast`, `.mir-toast-success`, `.mir-toast-error`, `.mir-toast-info`
- `.section-icon-header`, `.section-icon`, `.section-icon-indigo`

## Classes scoped com prefixo usr-
- `.usr-section`, `.usr-section-header`, `.usr-section-title`, `.usr-section-sub`, `.usr-section-header-right`
- `.usr-widget-owner`, `.usr-widget-author`, `.usr-widget-visitor` (widget-card ::before color)
- `.usr-widget-icon-owner`, `.usr-widget-icon-author`, `.usr-widget-icon-visitor`
- `.usr-col-avatar`, `.usr-col-body`, `.usr-col-username`, `.usr-col-role`, `.usr-col-status`, `.usr-col-date`
- `.usr-avatar`, `.usr-avatar-initials`
- `.usr-body`, `.usr-name`, `.usr-email`
- `.usr-username`
- `.usr-role-badge`, `.usr-role-owner`, `.usr-role-author`, `.usr-role-visitor`
- `.usr-date`

## Larguras de colunas
- Avatar: 36px fixo
- Usuario (nome+email): flex:1
- Username: 120px fixo
- Role: 90px fixo, centralizado
- Status: 90px fixo, centralizado
- Data: 90px fixo, alinhado a direita
- Acoes: 68px fixo (via plh-actions global)

## Avatar com iniciais
- Se o usuario tem foto valida: img circular
- Senao: circulo colorido com iniciais (2 letras: primeira do nome + primeira do sobrenome)
- Cores rotacionadas por user id % 7

## Role badges
- Owner: bg #e0e7ff, color #3730a3 (indigo)
- Author: bg #d1fae5, color #065f46 (verde)
- Visitor: bg #f3f4f6, color #6b7280 (cinza)