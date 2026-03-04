<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // --- HEADER ---
        // Item Raiz
        $blog = Menu::create([
            'type'      => 'header',
            'title'     => 'Blog',
            'url'       => '#', // Como terá filhos, a URL deve ser '#'
            'target'    => '_self',
            'order'     => 0,
            'is_active' => true,
        ]);

        // Subitens do Blog
        Menu::create([
            'type'      => 'header',
            'parent_id' => $blog->id,
            'title'     => 'Últimas Notícias',
            'url'       => '/posts',
            'order'     => 0,
            'is_active' => true,
        ]);

        Menu::create([
            'type'      => 'header',
            'parent_id' => $blog->id,
            'title'     => 'Categorias',
            'url'       => '/categories',
            'order'     => 1,
            'is_active' => true,
        ]);

        // Item Raiz Simples
        Menu::create([
            'type'      => 'header',
            'title'     => 'Contato',
            'url'       => '/contact',
            'order'     => 1,
            'is_active' => true,
        ]);

        // --- FOOTER ---
        Menu::create([
            'type'      => 'footer',
            'title'     => 'Política de Privacidade',
            'url'       => '/privacy',
            'order'     => 0,
            'is_active' => true,
        ]);
    }
}