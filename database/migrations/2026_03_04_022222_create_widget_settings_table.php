<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION: Tabela de Configurações dos Widgets
 *
 * Esta é a peça mais importante da arquitetura.
 *
 * PROBLEMA do código antigo:
 * Colocar todas as configs na tabela principal criava dezenas de colunas
 * nullable. Um widget de "busca" não usa category_limit, slide_images, etc.
 * A tabela ficava 80% vazia — desperdício e falta de clareza.
 *
 * SOLUÇÃO (padrão EAV — Entity-Attribute-Value):
 * Cada configuração é uma LINHA nessa tabela, com chave e valor.
 * Widget de categorias tem suas configs. Widget de tags tem as suas.
 * Cada um usa exatamente o que precisa.
 *
 * Exemplo de dados:
 * | widget_id | key           | value        | type    |
 * |-----------|---------------|--------------|---------|
 * | 1         | display_type  | most_posts   | string  |
 * | 1         | limit         | 8            | integer |
 * | 2         | period        | week         | string  |
 * | 2         | limit         | 5            | integer |
 *
 * A coluna "type" guarda como o valor deve ser interpretado na hora
 * de ler (string, integer, boolean, json). Veja getSetting() no Model.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira: qual widget esta config pertence
            $table->foreignId('widget_id')
                  ->constrained('sidebar_widgets')
                  ->cascadeOnDelete();

            // Nome da configuração — ex: 'limit', 'display_type', 'selected_ids'
            $table->string('key');

            // Valor sempre armazenado como texto.
            // Integers viram "8", booleans viram "1"/"0", arrays viram JSON string.
            $table->text('value')->nullable();

            // Tipo para fazer o cast correto na hora de ler
            // Valores possíveis: 'string', 'integer', 'boolean', 'json'
            $table->string('type')->default('string');

            $table->timestamps();

            // Garante que não existam duas configs com a mesma chave no mesmo widget
            $table->unique(['widget_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_settings');
    }
};