---
key: posts-listing-refactor-requirements
summary: Requisitos para refatorar a listagem de posts do estilo Bootstrap/DeskApp para o design system mir-
---

# Requisitos - Refatoracao da Listagem de Posts

## Objetivo
Migrar a pagina de listagem de posts do layout Bootstrap/DeskApp antigo para o novo design system mir-, seguindo o mesmo padrao ja aplicado na pagina de categorias.

## Funcionalidades
- Page Header Action com titulo, badge de contagem, botao lixeira e botao novo post
- Stat Widgets (Total, Publicados, Rascunhos, Privados)
- Section Card com busca e filtro por status
- Data rows flexbox substituindo tabela HTML
- Status pill clicavel com ciclo published > draft > private > published
- Modal de exclusao (soft delete) com Livewire properties
- Paginacao com seletor por pagina, botoes e contagem
- Loading bar e overlay
- Toast para feedback
- Empty state quando nenhum post encontrado
