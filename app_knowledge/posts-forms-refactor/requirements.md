---
key: posts-forms-refactor-requirements
summary: Requisitos para refatoracao das paginas de criar, editar e pendentes de posts
---

# Requisitos - Refatoracao Formularios de Posts

## Paginas afetadas
- `create.blade.php` - Criar Post
- `edit.blade.php` - Editar Post
- `pending.blade.php` - Posts Pendentes

## O que foi solicitado
1. Remover breadcrumbs DeskApp e footers de botoes
2. Adicionar Page Header Action com botoes mir-btn
3. Layout 2 colunas com `.form-layout` (grid 1fr 380px) para create/edit
4. Section Cards com `.section-icon-header` para agrupar campos
5. Substituir form-control por mir-input, custom-checkbox por mir-switch, custom-radio por status-pills
6. Upload area estilizada para imagem destacada
7. Pending: substituir tabela por section card com data rows flexbox
8. Manter Quill editor intacto (scripts e modais)
9. Manter Livewire post-downloads como esta
