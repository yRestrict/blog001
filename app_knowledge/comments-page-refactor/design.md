---
key: comments-page-refactor-design
summary: Detalhes de implementacao da refatoracao da pagina de comentarios
---

# Design - Refatoracao de Comentarios

## Arquivos modificados
- `resources/views/dashboard/comments/index.blade.php` - Removido breadcrumb DeskApp
- `app/Livewire/Admin/CommentsModeration.php` - Adicionado perPage, deletingCommentId, prepareDelete/cancelDelete
- `resources/views/livewire/admin/comments-moderation.blade.php` - Reescrito completo

## Estrutura de colunas (Data Row)
| Coluna | Largura | Classe |
|---|---|---|
| Autor | 160px fixo | cmt-author |
| Comentario | flex: 1 1 0 | cmt-body |
| Post | 140px fixo | cmt-post |
| Status | 90px fixo | mir-status |
| Data | 90px fixo | cmt-date |
| Acoes | 100px fixo | mir-actions |

## Widgets (clicaveis como filtro)
- Pendentes: widget-card-pending (amber)
- Aprovados: widget-card-approved (verde)
- Rejeitados: widget-card-rejected (vermelho)
- Total: widget-card-comments (amber)
- Widget ativo: classe cmt-widget-active (borda indigo)

## CSS scoped com prefixo cmt-
Classes especificas: cmt-section, cmt-author, cmt-body, cmt-post, cmt-date, cmt-reply-badge, cmt-mute-btn, cmt-widget-active, cmth-*

## Classes globais reutilizadas
page-header-action, widgets-grid, widget-card, mir-section, mir-table-header, mir-data-list, mir-data-row, mir-divider, mir-status, mir-action-btn, mir-pagination, mir-modal-*, mir-empty-state, mir-toast, mir-input, mir-switch-*, mir-btn-*
