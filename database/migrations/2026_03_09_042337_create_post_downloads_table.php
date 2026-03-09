<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->string('label');              // nome do botão ex: "Download PDF"
            $table->string('url')->nullable();    // link externo
            $table->string('file')->nullable();   // arquivo enviado (storage)
            $table->enum('position', ['left', 'center', 'right', 'block'])->default('left');
            // left = esquerda, center = meio, right = direita, block = um embaixo do outro
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->cascadeOnDelete();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_downloads');
    }
};
