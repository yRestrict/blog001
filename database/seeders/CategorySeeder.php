<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // TECNOLOGIA (parent_id = 1)
        // =========================

        Category::create([
            'name' => 'Programação',
            'slug' => 'programacao',
            'parent_category_id' => 1,
            'description' => 'Tudo sobre código e desenvolvimento.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Inteligência Artificial',
            'slug' => 'inteligencia-artificial',
            'parent_category_id' => 1,
            'description' => 'Machine Learning, IA e automações.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Desenvolvimento Web',
            'slug' => 'desenvolvimento-web',
            'parent_category_id' => 1,
            'description' => 'Frontend, Backend e Full Stack.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Mobile',
            'slug' => 'mobile',
            'parent_category_id' => 1,
            'description' => 'Apps Android e iOS.',
            'status' => true
        ]);

        Category::create([
            'name' => 'DevOps',
            'slug' => 'devops',
            'parent_category_id' => 1,
            'description' => 'Deploy, servidores e infraestrutura.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Cybersegurança',
            'slug' => 'cyberseguranca',
            'parent_category_id' => 1,
            'description' => 'Segurança digital e proteção de dados.',
            'status' => true
        ]);

        // =========================
        // ESTILO DE VIDA (parent_id = 2)
        // =========================

        Category::create([
            'name' => 'Produtividade',
            'slug' => 'produtividade',
            'parent_category_id' => 2,
            'description' => 'Dicas para ser mais produtivo.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Saúde',
            'slug' => 'saude',
            'parent_category_id' => 2,
            'description' => 'Bem-estar físico e mental.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Finanças',
            'slug' => 'financas',
            'parent_category_id' => 2,
            'description' => 'Educação financeira e investimentos.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Carreira',
            'slug' => 'carreira',
            'parent_category_id' => 2,
            'description' => 'Mercado de trabalho e crescimento profissional.',
            'status' => true
        ]);

        Category::create([
            'name' => 'Empreendedorismo',
            'slug' => 'empreendedorismo',
            'parent_category_id' => 2,
            'description' => 'Negócios e startups.',
            'status' => true
        ]);
    }
}