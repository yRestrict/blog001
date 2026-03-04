<?php 

namespace Database\Seeders;


use App\Models\ParentCategory;
use Illuminate\Database\Seeder;

class ParentCategorySeeder extends Seeder
{
    public function run(): void
    {
        ParentCategory::create(['name' => 'Tecnologia', 'slug' => 'tecnologia', 'ordering' => 1]);
        ParentCategory::create(['name' => 'Estilo de Vida', 'slug' => 'estilo-de-vida', 'ordering' => 2]);
    }
}