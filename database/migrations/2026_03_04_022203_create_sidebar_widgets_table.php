<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION: Tabela de Widgets
 *
 * Um widget é um bloco dentro de uma sidebar. Exemplos:
 * - Widget de busca
 * - Widget de categorias
 * - Widget de posts populares
 *
 * Por que separar em tabela própria?
 * Porque uma sidebar pode ter VÁRIOS widgets, e cada widget
 * tem um tipo e posição próprios. Relacionamento: 1 sidebar → N widgets.
 *
 * As CONFIGURAÇÕES específicas de cada widget ficam em outra tabela
 * (widget_settings), porque cada tipo de widget tem configs diferentes.
 * Assim essa tabela fica limpa, sem colunas nullable em excesso.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sidebar_widgets', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira: qual sidebar este widget pertence
            // cascadeOnDelete() = se deletar a sidebar, os widgets são deletados junto
            $table->foreignId('sidebar_id')
                  ->constrained('sidebars')
                  ->cascadeOnDelete();

            // Tipo do widget — determina qual view e lógica usar
            // Exemplos: 'search', 'categories', 'tags', 'popular_posts', etc.
            $table->string('type');

            // Título exibido acima do widget (ex: "Posts Populares")
            // Nullable pois alguns widgets não precisam de título (ex: busca)
            $table->string('title')->nullable();

            // Ordem de exibição dentro da sidebar (menor = mais acima)
            // Controlado via drag & drop no painel
            $table->integer('position')->default(0);

            // Se false, o widget não aparece no frontend
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidebar_widgets');
    }
};