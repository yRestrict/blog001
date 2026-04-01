# CLAUDE.md — Blog001 Dashboard UI Design Guide

## Contexto

O cliente desenvolveu este sistema usando o template **DeskApp** (Bootstrap 4). Posteriormente, uma IA refatorou a UI da **página de categorias**, criando um novo padrão visual que o cliente aprovou. A partir daí, construímos um wireframe interativo da página de posts (`.claude/wireframe-posts.html`) que serviu para validar e refinar o design system. Este guia documenta todos os princípios extraídos e refinados durante esse processo.

**Wireframe de referência:** `.claude/wireframe-posts.html` — contém 4 telas interativas (Listagem, Criar Post, Lixeira, Loading) que servem como fonte de verdade visual.

---

## Análise Comparativa: Antigo vs Novo

| Aspecto | Antigo (DeskApp/Bootstrap) | Novo (Design System mir-) |
|---|---|---|
| **Navegação da página** | Breadcrumb + botões soltos no corpo | **Page Header Action** no topo com título + botões filled |
| **Layout de dados** | `<table>` HTML com `thead-light` | Section cards + data rows flexbox |
| **Botões** | Classes Bootstrap (`btn btn-primary`, etc.) | Botões filled com cor sólida, tamanho padronizado |
| **Status** | `badge` Bootstrap clicável | Pill customizado `.mir-status` com bolinha indicadora |
| **Ações** | Botões textuais (`btn btn-sm btn-info/danger`) | Botões ícone 30x30 com hover colorido + tooltips |
| **Exclusão** | Confirmação inline ("Confirmar? Sim/Não") | Modal dedicado com overlay + backdrop blur |
| **Feedback visual** | Nenhum | Tooltips inteligentes, loading skeleton, toasts customizados |
| **Paginação** | `{{ $posts->links() }}` simples | Barra com seletor por página + botões + contagem |
| **CSS** | Global do template | Scoped `<style>` por componente Blade |

---

## Design System "mir-" — Guia de Referência

### 1. Paleta de Cores

```
Primária (ações/CTA):
  - Indigo:       #6366f1
  - Hover:        #4f46e5

Sucesso (status ativo / restaurar):
  - Sólido:       #10b981
  - Hover:        #059669
  - Background:   #d1fae5
  - Texto:        #065f46

Perigo (exclusão / inativo):
  - Sólido:       #ef4444
  - Hover:        #dc2626
  - Background:   #fee2e2
  - Texto:        #991b1b

Privado:
  - Background:   #e0e7ff
  - Texto:        #3730a3
  - Ring:         #6366f1

Info (badges):
  - Background:   #ede9fe
  - Texto:        #6d28d9

Neutros:
  - Texto primário:    #1a1d23
  - Texto secundário:  #6d7279 / #9ca3af
  - Bordas:            #e9ecef / #f0f0f0 / #e5e7eb
  - Background cards:  #fff
  - Background hover:  #f9fafb
  - Background footer: #fafafa
```

### 2. Tipografia

```
Font family:        Inter (Google Fonts), weight 300–800

Page header título: 1.25rem, weight 800, #1a1d23
Títulos seção:      .95rem, weight 700, #1a1d23
Subtítulos:         .78rem, #9ca3af
Nomes de item:      .875rem, weight 600, #1a1d23
Slugs/meta:         .72rem, monospace (ui-monospace), #9ca3af
Labels:             .8rem, weight 600, #374151
Inputs:             .85rem, #1a1d23
Botões:             .82rem, weight 600
Badges:             .72rem, weight 700
Table header:       .68rem, weight 700, uppercase, letter-spacing .6px, #9ca3af
Paginação:          .78rem, weight 500, #9ca3af
```

### 3. Espaçamento e Layout

```
Card section:
  - border-radius: 10px
  - border: 1px solid #e9ecef
  - box-shadow: 0 1px 4px rgba(0,0,0,.05)
  - margin-bottom: 24px

Section header:
  - padding: 16px 20px
  - border-bottom: 1px solid #f0f0f0
  - display: flex, space-between, center

Rows:
  - padding: 12px 20px
  - gap: 14px
  - border-bottom: 1px solid #f5f5f5
  - hover: background #f9fafb

Page header action:
  - margin-bottom: 24px

Widgets grid:
  - grid 4 colunas, gap: 16px
  - margin-bottom: 24px

Gaps:
  - Header action botões: 10px
  - Meta items: 8px
  - Action buttons: 4px
  - Modal footer: 10px

Dividers verticais:
  - width: 1px, height: 28px
  - background: #e9ecef
  - margin: 0 10px
```

### 4. Componentes

---

#### 4.0 Page Header Action (`.page-header-action`) — OBRIGATÓRIO

Toda página do dashboard deve começar com um **Page Header Action**. Substitui breadcrumbs e botões dispersos, centralizando título, contexto e ações no topo do conteúdo.

**Por que usar:**
- **Acessibilidade**: o usuário sempre sabe onde está e o que pode fazer sem rolar a página
- **Previsibilidade**: ações como Salvar, Voltar, Criar ficam sempre no mesmo lugar (topo direita)
- **Hierarquia visual**: separa identidade da página do conteúdo
- **Elimina redundância**: não precisa repetir botões de ação no footer de formulários

**Estrutura:**
```
.page-header-action
├── .page-header-left
│   ├── .page-header-title      → 1.25rem, weight 800, #1a1d23
│   │   └── .page-header-count  → badge opcional (contagem)
│   └── .page-header-sub        → .82rem, #9ca3af
└── .page-header-right
    ├── [botão de navegação]     → mir-btn-neutral (branco, Voltar/Lixeira)
    ├── [botão contextual]       → mir-btn-success ou mir-btn-danger (opcional)
    └── [botão principal]        → mir-btn-primary-lg (sempre último)
```

**Exemplos por tipo de página:**

| Página | Título | Botões (esquerda → direita) |
|---|---|---|
| **Listagem** | "Posts" + badge `24` | `Lixeira` (neutral) → `+ Novo Post` (primary) |
| **Criar** | "Criar Post" | `← Voltar` (neutral) → `Criar Post` (primary) |
| **Editar** | "Editar Post" | `← Voltar` (neutral) → `Salvar Alterações` (primary) |
| **Lixeira** | "Lixeira" + badge vermelho `3` | `← Voltar` (neutral) → `Restaurar Todos` (success) → `Esvaziar` (danger) |
| **Settings** | "Configurações Gerais" | `Salvar Alterações` (primary) |

**Regras:**
- O botão primário (ação principal) é **sempre o último** da direita
- O botão neutral de navegação (Voltar) é **sempre o primeiro** da direita
- Em páginas de listagem, o badge de contagem fica ao lado do título
- **Nunca** usar footer de botões em formulários — os botões ficam no header

---

#### 4.1 Botões — Padrão Filled, Cor Sólida

Todos os botões usam **cor sólida flat** (sem gradiente), altura padronizada e sombras sutis.

**Tamanho padrão:**
```
height: 36px
padding: 0 16px
border-radius: 8px
font-size: .82rem
font-weight: 600
```

**Variantes:**

| Classe | Background | Hover | Sombra | Uso |
|---|---|---|---|---|
| `mir-btn-primary-lg` | `#6366f1` | `#4f46e5` | `rgba(99,102,241,.25)` | Ação principal (Criar, Salvar) |
| `mir-btn-neutral` | `#fff` + borda `#e5e7eb` | `#f9fafb` + borda `#d1d5db` | `rgba(0,0,0,.06)` | Navegação (Voltar, Lixeira) |
| `mir-btn-success` | `#10b981` | `#059669` | `rgba(16,185,129,.25)` | Ação positiva (Restaurar) |
| `mir-btn-danger` | `#ef4444` | `#dc2626` | `rgba(239,68,68,.25)` | Ação destrutiva (Excluir, Esvaziar) |
| `mir-btn-ghost` | `transparent` + borda `#e0e0e0` | `#f5f5f5` | nenhuma | Ação secundária (dentro de modais/painéis) |

**Regras:**
- **Sem gradiente** — todos usam cor sólida
- **Sombras sutis**: repouso `0 1px 3px` → hover `0 2px 6px`
- Hover escurece levemente o background (tom mais escuro da mesma cor)
- `mir-btn-neutral` é **branco** com borda e texto escuro — usado para botões de navegação (Voltar, Lixeira)
- Todos os botões do **Page Header Action** são filled (nunca ghost)
- `mir-btn-ghost` reservado para **dentro de modais e painéis de filtro**

---

#### 4.2 Section Card

Container principal para agrupar dados. Background branco, borda `#e9ecef`, radius 10px.

```
Section Card
├── Section Header    → título + subtítulo + controles (busca, filtro)
├── Table Header      → colunas uppercase, bg #f8fafc (opcional)
├── Loading Bar       → barra indigo animada (quando carregando)
├── Data Rows         → linhas de dados flexbox
└── Paginação         → seletor por página + botões + contagem
```

---

#### 4.3 Table Header (`.post-list-header`)

Header de colunas acima das rows, com fundo sutil e texto uppercase.

```
background: #f8fafc
padding: 10px 20px
border-bottom: 1px solid #e9ecef
font: .68rem, weight 700, uppercase, letter-spacing .6px, #9ca3af
```

As larguras das colunas do header devem **espelhar exatamente** as larguras das data rows.

---

#### 4.4 Largura de Colunas em Data Rows

Colunas de título/descrição devem **sempre ser as maiores** — ocupam todo o espaço restante. Demais colunas têm largura fixa.

| Coluna | Largura | Alinhamento |
|---|---|---|
| **Thumbnail** | `48px` fixo | — |
| **Título/Body** | `flex: 1 1 0` | esquerda |
| **Meta (badges)** | `160px` fixo | **direita** (`justify-content: flex-end`) |
| **Status pill** | `90px` mínimo | centro |
| **Ações** | `68px` fixo | centro |
| **Deleted at** (trash) | `140px` fixo | direita |
| **Divider** | `1px` + margin `10px` | — |

**Regras:**
- Apenas **uma coluna** por row usa `flex: 1` — sempre título/descrição/nome
- Todas as outras: `width` fixo + `flex-shrink: 0`
- Header da tabela deve usar as **mesmas larguras**
- `text-overflow: ellipsis` na coluna flex

---

#### 4.5 Data Row

Layout flexbox horizontal, substitui linhas de tabela:
```
[Thumb 48x48] [Body: nome + meta] [Meta: badges] | [Status] | [Ações]
```
- padding: 12px 20px, gap: 14px
- border-bottom: 1px solid #f5f5f5
- hover: background #f9fafb

---

#### 4.6 Status Pill (`.mir-status`)

Botão clicável com bolinha indicadora + texto. Três estados:

| Estado | Classe | Background | Texto | Ring |
|---|---|---|---|---|
| Publicado | `.is-published` | `#d1fae5` | `#065f46` | `#10b981` |
| Rascunho | `.is-draft` | `#f3f4f6` | `#6b7280` | `#9ca3af` |
| Privado | `.is-private` | `#e0e7ff` | `#3730a3` | `#6366f1` |

---

#### 4.7 Action Buttons (`.mir-action-btn`)

Botões 30x30px somente ícone. Transparentes por padrão, hover revela cor contextual:

| Ação | Hover bg | Hover borda | Hover cor |
|---|---|---|---|
| Edit | `#ede9fe` | `#c4b5fd` | `#5b21b6` |
| Delete | `#fee2e2` | `#fca5a5` | `#b91c1c` |
| Restore | `#d1fae5` | `#6ee7b7` | `#059669` |

---

#### 4.8 Tooltips — Posicionamento Inteligente

Tooltips aparecem via atributo `data-tooltip` e são posicionados dinamicamente via JavaScript para nunca ultrapassar os limites da tela.

**Ordem de tentativa de posição:** acima → abaixo → esquerda → direita.

**Estilo:**
```
background: #1e1e2e
color: #f1f5f9
font-size: .7rem, weight 600
border-radius: 6px
padding: 6px 11px
box-shadow: 0 4px 14px rgba(0,0,0,.25)
Seta: 5px, acompanha a posição
```

**Onde usar:**
- Botões de ação: "Editar post", "Mover para lixeira", "Excluir permanentemente", "Restaurar post"
- Status pills: "Clique para alterar status" (listagem) / "Status antes da exclusão" (lixeira)
- Badges de contagem: "3 tags", "5 tags", etc.

---

#### 4.9 Paginação (`.post-pagination`)

Barra no rodapé da section card, dividida em 3 áreas via `space-between`:

```
[Por página: [6 ▾]]          [◀ 1 2 3 4 ▶]          [Mostrando 1–6 de 24]
     left                       center                      right
```

**Seletor "Por página":** opções 6, 10, 25, 50
**Botões de página:** `min-width: 32px`, `height: 32px`, ativo = fundo `#6366f1`
**Texto:** `.78rem`, `#9ca3af`

---

#### 4.10 Loading State

Composto por 3 elementos que indicam carregamento:

1. **Loading bar** (`.post-loading-bar`): barra de 3px com animação de slide indigo, posicionada entre o header e o table header
2. **Skeleton rows**: retângulos cinza (`#e5e7eb` / `#f3f4f6`) que simulam o formato dos dados
3. **Overlay** (`.post-loading-overlay`): camada `rgba(255,255,255,.6)` + `backdrop-filter: blur(1px)` sobre as rows

Widgets também exibem skeleton (valores cinza, ícones com opacidade reduzida).

---

#### 4.11 Dropdown de Filtros

Painel dropdown ancorado à direita da barra de busca. Botão com ícone de sliders + badge de contagem de filtros ativos.

**Estrutura:**
```
[Botão "Filtros" + badge contagem]
└── Painel dropdown (280px)
    ├── Header: "Filtros" + "Limpar tudo"
    ├── Body: grupos de chips clicáveis
    │   ├── Categoria (chips)
    │   ├── Autor (chips)
    │   ├── Período (chips)
    │   └── Opções (chips com dot colorido)
    └── Footer: botões Ghost "Fechar" + Primary "Aplicar"
```

**Chip ativo:** `border: #6366f1`, `background: #ede9fe`, `color: #4f46e5`

---

#### 4.12 Badges

| Tipo | Classe | Background | Cor |
|---|---|---|---|
| Contagem (tags) | `.mir-badge-count` | `#ede9fe` | `#6d28d9` |
| Relacionamento (categoria) | `.mir-badge-parent` | `#d1fae5` | `#065f46` |
| Destaque | `.post-badge-feat` | `#fef3c7` | `#92400e` |
| Excluído em | `.mir-badge-deleted` | `#fee2e2` | `#991b1b` |
| Contagem no header | `.page-header-title-count` | `#ede9fe` | `#6d28d9` |
| Contagem lixeira | (variante) | `#fee2e2` | `#991b1b` |

---

#### 4.13 Stat Widgets (`.widget-card`) — Opcional para listagens

Grid de 4 colunas entre o Page Header Action e a Section Card.

**Card:** background branco, radius 10px, borda `#e9ecef`, faixa lateral 3px colorida, hover `translateY(-2px)`.

| Tipo | Faixa lateral | Ícone bg | Ícone cor |
|---|---|---|---|
| Total | `#f59e0b` | `#fef3c7` | `#d97706` |
| Publicado | `#10b981` | `#d1fae5` | `#059669` |
| Rascunho | `#9ca3af` | `#f3f4f6` | `#6b7280` |
| Privado | `#6366f1` | `#e0e7ff` | `#4f46e5` |

---

#### 4.14 Modal (`.mir-modal-*`)

```
Overlay:   fixed inset 0, rgba(17,24,39,.55), backdrop-filter blur(2px)
Dialog:    max-width 540px, animação scale(.97) + translateY(-12px)
Content:   border-radius 14px, shadow 0 20px 60px rgba(0,0,0,.18)
Header:    ícone contextual (add=indigo, edit=verde, delete=vermelho) + título + subtítulo
Body:      padding 22px
Footer:    background #fafafa, border-top #f0f0f0, botões ghost + primary/danger
```

---

#### 4.15 Form Inputs (`.mir-input`)
```
Border:    1.5px solid #e5e7eb
Radius:    8px
Focus:     border #6366f1, shadow 0 0 0 3px rgba(99,102,241,.12)
Invalid:   border #ef4444, shadow rgba(239,68,68,.12)
```

#### 4.16 Switch Toggle (`.mir-switch-*`)
Track 38x22px, thumb 16x16px. Checked: track `#6366f1`, thumb desliza 16px.

#### 4.17 Empty State (`.mir-empty-state`)
Centralizado, padding 48px. Ícone 56x56 arredondado. Título + descrição + botão CTA.

#### 4.18 Toast (`.mir-toast`)
Fixo bottom-right. Variantes: success (verde), error (vermelho), info (roxo). Auto-dismiss 3.5s.

#### 4.19 Drag Handle (`.cat-handle`)
Ícone SVG 6-dot grip. Padrão `#c9cdd4`, hover `#6366f1`. cursor: grab / grabbing.

---

### 5. Transições

```
Padrão:        .15s ease
Hover botões:  .15s ease (background + box-shadow)
Modal entrada: .2s ease (scale + translateY)
Toast entrada: .25s ease (translateY)
Toast saída:   200ms ease (translateY + opacity)
Widget hover:  .2s ease (translateY + box-shadow)
Loading bar:   1.2s ease-in-out infinite (slide)
Tooltip:       .12s ease (opacity)
```

### 6. Ícones

SVGs inline para ações em rows. Tamanhos:
- Handle: 8x14, Edit: 13x13, Delete: 12x13, Plus: 11x11, Badge: 8x8

Font Awesome usado em: modais, empty states, tooltips de seção, ícones de widget, botões do header action.

### 7. Convenção de Nomenclatura CSS

```
Prefixo mir-     → Classes reutilizáveis do design system (botões, modais, status, inputs, toasts, switches, empty states, tooltips)
Prefixo post-    → Classes específicas do contexto de posts (section, row, thumb, body, meta, info, list, pagination, loading)
Prefixo cat-     → Classes específicas do contexto de categorias
Prefixo page-    → Classes do page header action
Prefixo widget-  → Classes dos stat widgets
Prefixo filter-  → Classes do dropdown de filtros
Prefixo plh-     → Classes do table header (post list header)
```

Ao aplicar em novas páginas, usar `mir-` para componentes compartilhados e criar prefixo próprio para o contexto (ex: `tag-`, `user-`, `comment-`).

---

## Regras para Refatoração de Outras Páginas

1. **Toda página deve começar com um Page Header Action** — título + botões filled no topo. Eliminar breadcrumbs e footers de botão. Botões de navegação (Voltar) são brancos (`mir-btn-neutral`), ação principal sempre por último (`mir-btn-primary-lg`)
2. **Em listagens, adicionar Stat Widgets** entre o header action e a section card
3. **Substituir `<table>` por section cards + data rows** com colunas de largura padronizada (título = flex, demais = fixas)
4. **Adicionar Table Header** (`.post-list-header`) com texto uppercase e fundo `#f8fafc`
5. **Botões são filled com cor sólida** — sem gradiente, sem sombras pesadas. Variantes: primary, neutral, success, danger, ghost
6. **Substituir badges Bootstrap** por `.mir-status` com ring indicator (3 estados: publicado, rascunho, privado)
7. **Substituir confirmações inline** por modais `mir-modal-*` com Alpine.js
8. **Adicionar tooltips inteligentes** (`data-tooltip`) em botões de ação, status pills e badges. Posicionamento automático via JS
9. **Adicionar loading state** com barra animada + skeleton rows + overlay transparente
10. **Paginação com 3 zonas**: seletor por página (esquerda), botões (centro), contagem (direita)
11. **Adicionar dropdown de filtros** para listagens com múltiplos critérios
12. **Adicionar empty states** quando a lista estiver vazia
13. **CSS scoped no Blade** — cada componente Livewire com `<style>` próprio, reutilizando classes `mir-`
14. **Manter compatibilidade** — layout master (navbar, sidebar) permanece DeskApp/Bootstrap. Mudanças apenas na área de conteúdo

---

## Stack Técnica (Dashboard)

- **Backend:** Laravel 12 + Livewire 3
- **Frontend base:** DeskApp Bootstrap 4 template
- **Reatividade:** Livewire + Alpine.js
- **CSS:** custom.css (design system mir-) + styles scoped por componente
- **Drag & Drop:** SortableJS + livewire-sortable
- **Fonte:** Inter (Google Fonts)
- **Ícones:** Font Awesome + SVGs inline
- **Tooltips:** JS nativo com posicionamento inteligente
