<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable(); // null = visitante
            $table->unsignedBigInteger('parent_id')->nullable(); // null = comentário raiz, preenchido = reply

            // Dados do visitante (quando não logado)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            $table->text('body');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('ip_address', 45)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->cascadeOnDelete();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('comments')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};