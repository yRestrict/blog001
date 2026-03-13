<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostLike as PostLikeModel;
use App\Notifications\NewReactionNotification;
use Livewire\Component;

class PostLike extends Component
{
    public Post    $post;
    public int     $likesCount    = 0;
    public int     $dislikesCount = 0;
    public ?string $userReaction  = null;

    public function mount(Post $post): void
    {
        $this->post          = $post;
        $this->likesCount    = $post->likes()->where('type', 'like')->count();
        $this->dislikesCount = $post->likes()->where('type', 'dislike')->count();

        $existing = PostLikeModel::where('post_id', $post->id)
            ->where('ip_address', request()->ip())
            ->first();

        $this->userReaction = $existing?->type;
    }

    public function react(string $type): void
    {
        $ip       = request()->ip();
        $existing = PostLikeModel::where('post_id', $this->post->id)
            ->where('ip_address', $ip)
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
                $this->userReaction = null;
            } else {
                $existing->update(['type' => $type]);
                $this->userReaction = $type;
                // Notifica troca de reação
                NewReactionNotification::dispatch($existing->fresh());
            }
        } else {
            $like = PostLikeModel::create([
                'post_id'    => $this->post->id,
                'ip_address' => $ip,
                'session_id' => session()->getId(),
                'type'       => $type,
            ]);
            $this->userReaction = $type;
            NewReactionNotification::dispatch($like);
        }

        $this->likesCount    = PostLikeModel::where('post_id', $this->post->id)->where('type', 'like')->count();
        $this->dislikesCount = PostLikeModel::where('post_id', $this->post->id)->where('type', 'dislike')->count();
    }

    public function render()
    {
        return view('livewire.post-like');
    }
}