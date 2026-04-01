---
key: posts-listing-refactor-decisions
summary: Decisoes tecnicas da refatoracao da listagem de posts
---

# Decisoes - Refatoracao da Listagem de Posts

## Contadores como variaveis da view
Os contadores (totalPosts, publishedPosts, etc.) sao calculados no metodo render() e passados como variaveis da view, em vez de usar computed properties do Livewire 3. Isso simplifica o acesso no blade.

## Modal via Livewire properties
O modal de exclusao usa $deletingPostId e $deletingPostTitle como properties do Livewire, ao inves de state Alpine.js puro. O Alpine.js e usado apenas para o listener de ESC (keydown.escape).

## Toggle status com 3 estados
O toggleStatus cicla: published > draft > private > published. Diferente do comportamento anterior que alternava apenas entre published e draft.

## Toast container scoped
Cada componente Livewire tem seu proprio toast container (#post-toast-container), seguindo o padrao do componente de categorias (#cat-toast-container).

## Soft delete
O deletePost() usa soft delete (Model SoftDeletes trait), movendo para lixeira em vez de excluir permanentemente.
