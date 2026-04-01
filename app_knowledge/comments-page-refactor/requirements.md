---
key: comments-page-refactor-requirements
summary: Requisitos da refatoracao da pagina de comentarios para o design system mir-
---

# Requisitos - Refatoracao de Comentarios

## Objetivo
Refatorar a pagina de moderacao de comentarios do dashboard, migrando do layout DeskApp/Bootstrap para o design system mir-.

## Funcionalidades mantidas
- Filtro por status (pendente, aprovado, rejeitado)
- Busca por conteudo ou nome
- Moderacao: aprovar, rejeitar, mover para lixeira
- Lixeira: restaurar, excluir permanentemente
- Aprovar todos os pendentes
- Modal de mute de notificacoes por post
- Contadores de status
- Paginacao

## Novas funcionalidades de UI
- Page Header Action com titulo e botoes
- Stat Widgets clicaveis como filtro
- Section Card com busca e select no header
- Table Header uppercase
- Data Rows flexbox com colunas padronizadas
- Status pills mir-status com estados is-pending, is-approved, is-rejected
- Modal mir-modal para exclusao (substituindo wire:confirm)
- Paginacao 3 zonas com seletor por pagina
- Tooltips inteligentes
- Loading state com barra animada e overlay
- Empty state
- Toast notifications
