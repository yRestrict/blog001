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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['header', 'footer']);

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->restrictOnDelete();

            $table->string('title');
            $table->string('url')->default('#');
            $table->enum('target', ['_self', '_blank'])->default('_self');

            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
