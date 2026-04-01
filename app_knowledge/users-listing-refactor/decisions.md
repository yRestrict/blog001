---
key: users-listing-refactor-decisions
summary: Decisoes de implementacao da refatoracao da listagem de usuarios
---

# Decisoes - Refatoracao da Listagem de Usuarios

## Decisoes tomadas

### 1. Widgets por role em vez de por status
- Escolhido mostrar contadores de Owner/Author/Visitor nos widgets
- Motivo: roles sao mais relevantes para gestao de usuarios do que status

### 2. Modal de exclusao via Livewire properties
- Seguiu o padrao do posts.blade.php (prepareDelete/cancelDelete/deleteUser)
- Em vez de Alpine.js com dispatch (como em categories), usou-se properties Livewire
- Motivo: consistencia com a pagina de posts que e mais recente

### 3. Status pill usa is-active/is-inactive do CSS global
- As classes .mir-status.is-active e .mir-status.is-inactive ja existem no custom.css
- Status "banned", "pending", "rejected" sao mapeados visualmente para is-inactive
- O texto do pill mostra o status real (Banido, Pendente, etc)

### 4. Removido dropdown de acoes Bootstrap
- O dropdown original tinha acoes de promover, demover, banir, deletar
- Simplificado para apenas editar (link) e excluir (modal)
- Promover/demover/banir continuam disponiveis via toggling de status ou na pagina de edicao
- Motivo: design system mir- usa botoes de acao 30x30 (maximo 2-3 por row)

### 5. perPage default 10
- Alterado de 20 para 10 com seletor de 6/10/25/50
- Motivo: padrao do design system de paginacao

### 6. Toast via dispatch('notify')
- Adicionado dispatch de eventos notify nos metodos do Livewire
- JS de toast segue o padrao exato de posts e categories