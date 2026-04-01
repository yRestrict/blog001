<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Media;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';
    public bool $showResults = false;

    public function updatedQuery(): void
    {
        $this->showResults = strlen($this->query) >= 2;
    }

    public function close(): void
    {
        $this->query = '';
        $this->showResults = false;
    }

    public function render()
    {
        $results = [];

        if (strlen($this->query) >= 2) {
            $q = $this->query;

            $posts = Post::where('title', 'like', "%{$q}%")
                ->limit(5)
                ->get(['id', 'title', 'status', 'created_at']);

            if ($posts->isNotEmpty()) {
                $results['Posts'] = $posts->map(fn($p) => [
                    'id'    => $p->id,
                    'label' => $p->title,
                    'meta'  => ucfirst($p->status) . ' · ' . $p->created_at->format('d/m/Y'),
                    'url'   => route('admin.posts.edit', $p->id),
                    'icon'  => 'fa-newspaper',
                ]);
            }

            $categories = Category::where('name', 'like', "%{$q}%")
                ->limit(5)
                ->get(['id', 'name', 'slug']);

            if ($categories->isNotEmpty()) {
                $results['Categorias'] = $categories->map(fn($c) => [
                    'id'    => $c->id,
                    'label' => $c->name,
                    'meta'  => $c->slug,
                    'url'   => route('admin.categories.index'),
                    'icon'  => 'fa-folder',
                ]);
            }

            $tags = Tag::where('name', 'like', "%{$q}%")
                ->limit(5)
                ->get(['id', 'name', 'slug']);

            if ($tags->isNotEmpty()) {
                $results['Tags'] = $tags->map(fn($t) => [
                    'id'    => $t->id,
                    'label' => $t->name,
                    'meta'  => $t->slug,
                    'url'   => route('admin.tags.index'),
                    'icon'  => 'fa-tag',
                ]);
            }

            $users = User::where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('username', 'like', "%{$q}%")
                ->limit(5)
                ->get(['id', 'name', 'email', 'role']);

            if ($users->isNotEmpty()) {
                $results['Usuários'] = $users->map(fn($u) => [
                    'id'    => $u->id,
                    'label' => $u->name,
                    'meta'  => $u->email . ' · ' . ucfirst($u->role?->value ?? $u->role),
                    'url'   => route('admin.users.edit', $u->id),
                    'icon'  => 'fa-user',
                ]);
            }

            $media = Media::where('original_name', 'like', "%{$q}%")
                ->limit(5)
                ->get(['id', 'original_name', 'extension', 'created_at']);

            if ($media->isNotEmpty()) {
                $results['Mídia'] = $media->map(fn($m) => [
                    'id'    => $m->id,
                    'label' => $m->original_name,
                    'meta'  => strtoupper($m->extension) . ' · ' . $m->created_at->format('d/m/Y'),
                    'url'   => route('admin.media'),
                    'icon'  => 'fa-file',
                ]);
            }
        }

        return view('livewire.admin.global-search', [
            'results' => $results,
        ]);
    }
}
