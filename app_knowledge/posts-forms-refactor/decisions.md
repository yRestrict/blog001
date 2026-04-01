---
key: posts-forms-refactor-decisions
summary: Decisoes tecnicas tomadas na refatoracao dos formularios de posts
---

# Decisoes - Refatoracao Formularios de Posts

## Form ID mantido como `post-form`
O arquivo `quill-scripts.blade.php` linha 145 referencia `document.getElementById('post-form')` para sincronizar o conteudo do Quill com o hidden input antes do submit. Manter esse ID e fundamental para o editor funcionar.

## Botao submit via onclick no header
O botao de submit no Page Header Action usa `onclick="document.getElementById('post-form').submit()"` pois esta fora do `<form>`. Isso dispara o evento submit que o Quill intercepta para sincronizar.

## Preview de imagem delegado ao quill-scripts
O `quill-scripts.blade.php` ja possui handler para `featured-image-input` change que mostra preview. No edit, adicionamos apenas logica extra para esconder a imagem atual (`current-image`).

## Status pills com classe dinamica server-side
As classes `selected-published/draft/private` sao renderizadas via Blade com `old()` para manter estado apos validacao. O JS no @push('scripts') adiciona toggle interativo.

## Hidden inputs para checkboxes
Adicionados `<input type="hidden" name="featured" value="0">` antes dos checkboxes para garantir que o valor 0 seja enviado quando desmarcado (padrao HTML nao envia checkbox desmarcado).

## Upload area como label
O `.upload-area` e um `<label for="featured-image-input">` que encapsula o `<input type="file" style="display:none">`, permitindo clique em toda a area.

## Pending: confirmacao inline mantida
Para rejeitar posts, mantivemos `onsubmit="return confirm(...)"` em vez de modal mir-, pois a pagina nao usa Alpine.js de forma extensiva e a interacao e simples.
