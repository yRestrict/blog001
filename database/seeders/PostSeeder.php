<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'author_id' => 1,
            'category_id' => 1,
            'title' => 'Meu Primeiro Post',
            'slug' => 'meu-primeiro-post',
            'content' => 'Conteúdo detalhado do post...',
            'status' => 'published',
            'featured' => true
        ]);
    }
}