<?php

namespace Database\Seeders;


use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'site_title'            => 'Meu Blog Tech',
            'site_email'            => 'contato@blog.com',
            'site_description'      => 'Slogan do blog',
            'site_meta_description' => 'Descrição padrão para SEO da home',
            'site_meta_keywords'    => 'blog, tecnologia, programação',
            'site_social_links'     => json_encode([
                'facebook'  => 'https://facebook.com/meublog',
                'instagram' => 'https://instagram.com/meublog',
            ]),
        ]);
    }
}