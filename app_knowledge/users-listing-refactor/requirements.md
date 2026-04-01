---
key: users-listing-refactor-requirements
summary: Requisitos para refatorar a listagem de usuarios do DeskApp para o design system mir-
---

# Requisitos - Refatoracao da Listagem de Usuarios

## Objetivo
Refatorar a pagina de listagem de usuarios do dashboard para seguir o design system "mir-", substituindo o layout DeskApp/Bootstrap por componentes modernos com section cards, data rows e modais.

## Requisitos funcionais
- Page Header Action com titulo "Usuarios" + badge de contagem + botao "Novo Usuario"
- Stat Widgets mostrando total, owners, authors e visitors
- Section Card com busca por nome/email/username, filtro por role e filtro por status
- Table Header com colunas: Avatar, Usuario, Username, Role, Status, Cadastro, Acoes
- Data Rows com avatar circular (foto ou iniciais), nome+email, username monospace, role badge, status pill, data, botoes de acao
- Paginacao de 3 zonas com seletor por pagina
- Modal de exclusao com confirmacao
- Empty state quando nenhum usuario encontrado
- Nao permitir excluir o proprio usuario logado
- Status pill clicavel para alternar ativo/inativo (exceto owners e usuario logado)