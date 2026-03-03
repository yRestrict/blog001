<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;

class FeaturedSlider extends Component
{
    public $featuredPosts;

    public function mount()
    {
        $this->featuredPosts = Post::with(['category', 'author'])
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