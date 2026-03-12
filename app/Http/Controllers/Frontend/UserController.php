<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\UserStatus;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class UserController extends Controller
{
    public function index($username)
    {
        $user = User::with(['socialLinks'])
                    ->where('username', $username)
                    ->where('status', UserStatus::Active)
                    ->first();

        if (! $user) {
            abort(404);
        }

        $posts = $user->posts()
                    ->where('status', 'published')
                    ->with(['category', 'tags'])
                    ->latest()
                    ->paginate(6)
                    ->withQueryString();

        // ── SEO ───────────────────────────────────────────────────────────────
        $siteName    = Setting::first()->site_title ?? config('app.name');
        $title       = $user->name . ' — ' . $siteName;
        $description = $user->bio
                        ?? "Confira todos os posts publicados por {$user->name} no {$siteName}.";
        $imageURL    = $user->profile
                        ? asset('uploads/author/' . $user->profile)
                        : '';

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($user->name);

        SEOTools::opengraph()->setUrl(request()->url());
        SEOTools::opengraph()->addProperty('type', 'profile');
        SEOTools::opengraph()->addProperty('profile:username', $user->username);
        if ($imageURL) SEOTools::opengraph()->addImage($imageURL);

        SEOTools::twitter()->setUrl(request()->url());
        if ($imageURL) SEOTools::twitter()->addImage($imageURL);

        return view('frontend.user.index', [
            'pageTitle' => 'Posts de ' . $user->name,
            'settings'  => Setting::first(),
            'user'      => $user,
            'posts'     => $posts,
        ]);
    }
}