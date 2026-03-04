<?php

namespace Database\Seeders;

use App\Models\UserSocialLink;
use Illuminate\Database\Seeder;

class UserSocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        UserSocialLink::create([
            'user_id' => 1,
            'facebook_url' => 'https://facebook.com/user1',
            'whatsapp_url' => 'https://wa.me/551199999999'
        ]);
    }
}