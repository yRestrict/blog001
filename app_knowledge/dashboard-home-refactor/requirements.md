---
key: dashboard-home-refactor-requirements
summary: Requisitos da refatoracao da pagina Dashboard (home) para o design system mir-
---

# Requisitos - Dashboard Home Refactor (Fase 8)

## Objetivo
Refatorar a pagina principal do dashboard (home) do estilo antigo DeskApp/Bootstrap para o design system mir-.

## O que foi solicitado
- Remover breadcrumb DeskApp da index.blade.php
- Page Header Action com titulo "Dashboard" e saudacao ao usuario
- 2 grids de Stat Widgets (Posts + Outros: tags, categorias, likes, comentarios pendentes)
- Atalhos rapidos (grid 4 colunas) com links para Novo Post, Categorias, Comentarios, Configuracoes
- Section Card "Ultimos Posts" com table header, data rows simplificadas (thumb + titulo + autor | status | data)
- Empty state quando nao houver posts
- CSS scoped com prefixo dash-
