<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Programação',
            'slug' => 'programacao',
            'parent_category_id' => 1,
            'description' => 'Tudo sobre código.',
            'status' => true
        ]);
    }
}