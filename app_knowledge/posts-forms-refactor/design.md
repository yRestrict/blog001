---
key: posts-forms-refactor-design
summary: Detalhes de implementacao da refatoracao dos formularios de posts
---

# Design - Refatoracao Formularios de Posts

## Estrutura Create/Edit
- Page Header Action: titulo + sub + botoes (Voltar neutral, Criar/Salvar primary)
- Erros de validacao: warning-box (design system) em vez de alert-danger Bootstrap
- Form layout: `.form-layout` (grid 1fr 380px)
- Coluna principal: Section "Conteudo" (indigo) + Section "SEO" (green)
- Sidebar: Section "Publicacao" (indigo) + Section "Imagem Destacada" (amber) + Section "Downloads" (cyan)

## Componentes usados
- `.post-section` + `.post-section-header` para cards
- `.section-icon-header` + `.section-icon` + variantes de cor
- `.mir-input` para inputs/selects/textareas
- `.status-pills` + `.status-pill` para radio de status
- `.mir-switch-*` para checkboxes de destaque e comentarios
- `.upload-area` para input de imagem
- `.img-preview` para preview de imagem atual (edit)
- `.form-timestamps` para datas de criacao/atualizacao (edit)
- `.form-divider` para separadores dentro de sections

## Pending
- `.mir-table-header` para cabecalho de colunas
- `.post-row` com avatar circular 36px, post-body, autor, categoria badge, data, action buttons
- `.mir-action-btn` com SVGs inline para visualizar/aprovar/rejeitar
- `.mir-empty-state` para lista vazia
- `.post-pagination` com 3 zonas

## Integracao critica
- Form ID deve ser `post-form` pois quill-scripts.blade.php referencia esse ID para sincronizar conteudo
- quill-scripts.blade.php ja possui preview de imagem, nao duplicar
- Edit page adiciona script extra para esconder imagem atual ao selecionar nova
