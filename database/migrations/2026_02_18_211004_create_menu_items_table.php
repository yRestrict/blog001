<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->onDelete('cascade');             

            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('menu_items')
                  ->onDelete('restrict');            

            $table->string('title');                 
            $table->string('url')->default('#');     
            $table->enum('target', ['_self', '_blank'])->default('_self'); 
            $table->unsignedInteger('order')->default(0); 
            $table->boolean('is_active')->default(true);  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};