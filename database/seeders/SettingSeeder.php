<?php

namespace Database\Seeders;


use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'site_title' => 'Meu Blog Tech',
            'site_email' => 'contato@blog.com',
            'site_social_links' => json_encode([
                'facebook' => 'https://facebook.com/meublog',
                'instagram' => 'https://instagram.com/meublog'
            ])
        ]);
    }
}