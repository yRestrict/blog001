<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostLike as PostLikeModel;

class PostLike extends Component
{
    public Post $post;
    public int  $likesCount = 0;
    public bool $liked      = false;

    public function mount(Post $post): void
    {
        $this->post       = $post;
        $this->likesCount = $post->likes()->count();
        $this->liked      = $post->isLikedByIp(request()->ip());
    }

    public function toggle(): void
    {
        $ip = request()->ip();

        $existing = PostLikeModel::where('post_id', $this->post->id)
                            ->where('ip_address', $ip)
                            ->first();

        if ($existing) {
            // Remove o like
            $existing->delete();
            $this->liked      = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            // Adiciona o like
            PostLikeModel::create([
                'post_id'    => $this->post->id,
                'ip_address' => $ip,
                'session_id' => session()->getId(),
            ]);
            $this->liked      = true;
            $this->likesCount = $this->likesCount + 1;
        }
    }

    public function render()
    {
        return view('livewire.post-like');
    }
}