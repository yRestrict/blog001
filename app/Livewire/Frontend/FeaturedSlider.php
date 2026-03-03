<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;

class FeaturedSlider extends Component
{
    public function render()
    {
        // Busca posts publicados e marcados como destaque
        $featuredPosts = Post::with(['category', 'author'])
            ->where('featured', true)
            ->where('status', 'published') // Filtra pelo status
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.frontend.featured-slider', [
            'featuredPosts' => $featuredPosts
        ]);
    }
}
