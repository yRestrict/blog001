<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Computed;

class FeaturedSlider extends Component
{
    #[Computed]
    public function featuredPosts()
    {
        // CORRECAO: era 'user', mas o Post->author() usa author_id
        return Post::with(['category', 'author'])
            ->where('featured', true)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.frontend.featured-slider');
    }
}