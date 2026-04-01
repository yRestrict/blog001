---
key: tags-refactor-decisions
summary: Decisões técnicas da refatoração de tags para design system mir-
---

# Decisões - Refatoração Tags

## Renomeação de Métodos
- `openAdd()` -> `openCreateModal()` (consistência com nomenclatura do plano)
- `openEdit()` -> `openEditModal()` (idem)
- `save()` -> `saveTag()` (idem)
- `confirmDelete()` -> `prepareDelete()` (separa ID e nome para o modal)
- `deleteTag(int $id)` -> `deleteTag()` sem parâmetro (usa $deletingTagId)

## Modal de Exclusão
- Implementado com Livewire puro ($deletingTagId / $deletingTagName) em vez de Alpine.js dispatch como nas categorias
- Motivo: simplicidade, já que tags não têm dependências complexas como categorias pai/filhas

## Sem Stat Widgets
- Tags não possuem status (ativo/inativo) como posts, então widgets de estatísticas não foram incluídos
- A contagem total fica no badge do Page Header Action

## CSS Scoped Mínimo
- Apenas o container de toast (#tag-toast-container) foi adicionado como scoped
- Todas as demais classes já existem no custom.css global

## Paginação
- Padrão alterado de 15 para 10 itens por página (alinhado com opções do seletor: 6, 10, 25, 50)
