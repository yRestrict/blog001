<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // 🔹 Backend / Programação
            'Laravel',
            'PHP',
            'Node.js',
            'API REST',
            'MySQL',
            'Docker',
            'Git',
            'Arquitetura de Software',

            // 🔹 Frontend
            'JavaScript',
            'Vue.js',
            'React',
            'Tailwind CSS',
            'HTML',
            'CSS',

            // 🔹 Mobile
            'Flutter',
            'Android',
            'iOS',

            // 🔹 Inteligência Artificial
            'Machine Learning',
            'Deep Learning',
            'Automação',

            // 🔹 DevOps / Infra
            'CI/CD',
            'Cloud Computing',
            'AWS',
            'Linux',

            // 🔹 Estilo de Vida
            'Produtividade',
            'Saúde Mental',
            'Finanças Pessoais',
            'Carreira Dev',
            'Empreendedorismo',
            'Freelancer'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}