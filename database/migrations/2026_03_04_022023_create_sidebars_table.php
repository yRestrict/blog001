<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('sidebars', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('type');
            $table->boolean('status')->default(true);
            $table->integer('limit')->default(10);
            $table->enum('period_type', ['week', 'month', 'total'])->default('week');
            $table->integer('order')->default(0);
            $table->boolean('fixed')->default(false);

            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->string('category_display_type')->default('most_posts');
            $table->integer('category_limit')->default(8);
            $table->integer('tag_limit')->default(12);

            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->json('social_data')->nullable();
            $table->json('selected_categories')->nullable();

            $table->string('tag_display_type')->default('most_posts');
            $table->json('selected_tags')->nullable();
            $table->json('slide_images')->nullable();
            $table->integer('slide_interval')->nullable()->default(5000);
            $table->boolean('slide_autoplay')->nullable()->default(true);
            $table->boolean('slide_controls')->nullable()->default(true);
            $table->boolean('slide_indicators')->nullable()->default(true);
            $table->integer('image_width')->default(300);
            $table->integer('image_height')->default(150);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidebars');
    }
};
