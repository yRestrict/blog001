---
key: tags-refactor-requirements
summary: Requisitos da refatoração da página de tags para o design system mir-
---

# Requisitos - Refatoração Tags

## Objetivo
Migrar a página de tags do layout DeskApp/Bootstrap para o design system mir-, seguindo o mesmo padrão visual das categorias e posts.

## Escopo
- Remover breadcrumb DeskApp do index.blade.php
- Substituir tabela HTML por section card + data rows flexbox
- Implementar modais mir- para criar/editar/excluir tags
- Adicionar paginação com 3 zonas (por página, botões, contagem)
- Adicionar Page Header Action com badge de contagem
- Adicionar loading state, empty state, tooltips e toasts
