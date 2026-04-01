---
key: dashboard-home-refactor-decisions
summary: Decisoes tecnicas da refatoracao da pagina Dashboard home
---

# Decisoes - Dashboard Home Refactor

## Decisoes tomadas
1. **Dashboard.php nao alterado** - O componente Livewire ja passava todos os dados necessarios (posts stats, tags, categorias, likes, comentarios pendentes, latestPosts com author/category)
2. **Status pill como span (nao button)** - Na dashboard os status sao apenas informativos, sem toggle
3. **Sem coluna de acoes** - Simplificacao para dashboard; usuario pode clicar em "Ver todos" para gerenciar
4. **Coluna Data adicionada** - Substituiu meta/acoes por uma coluna de data simples (100px, alinhada a direita)
5. **Atalhos rapidos** - Usam .quick-action global + grid scoped .dash-shortcuts-grid; cores via inline style para simplicidade
6. **Rotas usadas** - admin.posts.create, admin.categories.index, admin.comments.index, admin.settings, admin.posts.index, admin.posts.edit

## Sem debito tecnico introduzido
- Todas as classes CSS globais ja existiam no custom.css
- Nenhuma duplicacao de estilos globais no scoped CSS
