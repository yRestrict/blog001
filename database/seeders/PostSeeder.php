<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'post.png',
            'post2.png',
            'post3.png'
        ];

        $posts = [
            [
                'title' => 'Introdução ao Laravel 11',
                'category_id' => 1,
                'content' => 'Aprenda os fundamentos do Laravel 11, rotas, controllers e Eloquent.'
            ],
            [
                'title' => 'O que é Inteligência Artificial na prática?',
                'category_id' => 2,
                'content' => 'Entenda como a IA funciona e como aplicar em projetos reais.'
            ],
            [
                'title' => 'Guia Completo de Desenvolvimento Web Moderno',
                'category_id' => 3,
                'content' => 'Frontend, backend, APIs REST e boas práticas.'
            ],
            [
                'title' => 'Criando Apps Mobile com Flutter',
                'category_id' => 4,
                'content' => 'Passo a passo para criar seu primeiro app multiplataforma.'
            ],
            [
                'title' => 'O que é DevOps e por que você deveria aprender',
                'category_id' => 5,
                'content' => 'CI/CD, Docker, servidores e cultura DevOps.'
            ],
            [
                'title' => 'Noções Básicas de Cybersegurança',
                'category_id' => 6,
                'content' => 'Proteja seus sistemas contra ataques e vulnerabilidades.'
            ],
            [
                'title' => '5 Técnicas de Produtividade para Programadores',
                'category_id' => 7,
                'content' => 'Como produzir mais e evitar burnout na área tech.'
            ],
            [
                'title' => 'Como melhorar sua Saúde Mental trabalhando com tecnologia',
                'category_id' => 8,
                'content' => 'Dicas práticas para manter equilíbrio e foco.'
            ],
            [
                'title' => 'Organizando suas Finanças como Dev Freelancer',
                'category_id' => 9,
                'content' => 'Planejamento financeiro e controle de renda variável.'
            ],
            [
                'title' => 'Como Crescer na Carreira de Desenvolvedor',
                'category_id' => 10,
                'content' => 'Soft skills, portfólio e estratégias para subir de nível.'
            ],
        ];

        foreach ($posts as $index => $post) {

            $image = $images[$index % 3]; // alterna entre 3 imagens

            Post::create([
                'author_id' => 1,
                'category_id' => $post['category_id'],
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'content' => $post['content'],
                'thumbnail' => $image,
                'status' => 'published',
                'featured' => $index === 0 || $index === 2
            ]);
        }
    }
}