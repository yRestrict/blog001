---
key: comments-page-refactor-decisions
summary: Decisoes tecnicas tomadas na refatoracao da pagina de comentarios
---

# Decisoes - Refatoracao de Comentarios

## destroy() agora usa modal
- Antes: wire:confirm inline no botao
- Agora: prepareDelete(id) abre modal mir-modal, destroy() nao recebe id (usa $deletingCommentId)
- Motivo: consistencia com design system que usa modais dedicados

## perPage adicionado
- Propriedade publica $perPage = 10 com updatingPerPage() que reseta pagina
- Paginacao usa o padrao mir-pagination de 3 zonas identico ao posts.blade.php

## totalCount calculado no render
- Soma de pending + approved + rejected (nao inclui lixeira)
- Passado ao view para uso no badge do page header e no widget Total

## Widgets ocultados na lixeira
- @unless($showTrash) envolve o bloco de widgets
- Na lixeira, o page header mostra botao "Voltar" em vez de "Lixeira"

## Acoes por contexto
- Listagem normal: aprovar (check verde), rejeitar (x vermelho), lixeira (trash)
- Lixeira: restaurar (undo), excluir permanente (trash)
- Aprovar/rejeitar usam as mesmas classes mir-action-restore e mir-action-delete para hover colors

## Modal de mute refatorado
- Migrado de Bootstrap modal para mir-modal-* pattern
- Usa mir-switch-* em vez de custom-control custom-switch
