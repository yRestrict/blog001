<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\PostApprovedNotification;
use App\Notifications\PostPendingNotification;
use App\Notifications\PostRejectedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function PostPage()
    {
        return view('dashboard.post.index', [
            'pageTitle'    => 'Posts',
            'pendingCount' => Post::where('pending_review', true)->count(),
        ]);
    }

    public function postCreate()
    {
        $this->authorize('create', Post::class);

        return view('dashboard.post.create', [
            'pageTitle'      => 'Criar Post',
            'categorieshtml' => $this->buildCategoriesHtml(),
        ]);
    }

    public function postStore(Request $request)
    {
        $this->authorize('create', Post::class);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'required|exists:categories,id',
            'tags'             => 'nullable|string',
            'thumbnail'        => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'featured'         => 'nullable|boolean',
            'comment'          => 'nullable|boolean',
            'status'           => 'required|in:draft,published,private',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('thumbnail')) {
            $filename = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->move(public_path('uploads/posts'), $filename);
            $data['thumbnail'] = $filename;
        }

        $user = Auth::user();

        $data['author_id']      = $user->id;
        $data['comment']        = $request->boolean('comment');
        $data['content']        = $this->sanitizeContent($data['content']);
        $data['pending_review'] = false;

        // Author sem auto_approve não pode marcar como destaque
        $data['featured'] = $user->isOwner() || $user->autoApprovePosts()
            ? $request->boolean('featured')
            : false;

        $data['meta_keywords']    = $request->meta_keywords ?: $this->generateKeywords($request);
        $data['meta_description'] = $request->meta_description
            ?: Str::limit($this->extractPlainText($data['content']), 160);

        // Author sem auto_approve tentando publicar:
        // → status vira private (pode ver no frontend) + pending_review = true
        if (! $user->isOwner() && ! $user->autoApprovePosts() && $data['status'] === 'published') {
            $data['status']         = 'private';
            $data['pending_review'] = true;
        }

        $post = Post::create($data);
        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        // Notifica todos os owners que há um post aguardando aprovação
        if ($data['pending_review']) {
            $owners = User::where('role', 'owner')->get();
            foreach ($owners as $owner) {
                $owner->notify(new PostPendingNotification($post));
            }
        }

        $message = $data['pending_review']
            ? 'Post enviado para aprovação! Você pode visualizá-lo como privado enquanto aguarda.'
            : 'Post criado com sucesso!';

        return redirect()->route('admin.posts.index')->with('success', $message);
    }

    public function postEdit(Post $post)
    {
        $this->authorize('update', $post);

        return view('dashboard.post.edit', [
            'pageTitle'      => 'Editar Post',
            'post'           => $post->load('tags'),
            'categorieshtml' => $this->buildCategoriesHtml($post->category_id),
            'currentTags'    => $post->tags->pluck('name')->implode(', '),
        ]);
    }

    public function postUpdate(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'tags'             => 'nullable|string',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'featured'         => 'nullable|boolean',
            'comment'          => 'nullable|boolean',
            'status'           => 'required|in:draft,published,private',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail && file_exists(public_path('uploads/posts/' . $post->thumbnail))) {
                unlink(public_path('uploads/posts/' . $post->thumbnail));
            }
            $filename = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->move(public_path('uploads/posts'), $filename);
            $data['thumbnail'] = $filename;
        }

        $user = Auth::user();

        $data['comment']        = $request->boolean('comment');
        $data['content']        = $this->sanitizeContent($data['content']);
        $data['pending_review'] = false;

        // Author sem auto_approve não pode marcar como destaque
        $data['featured'] = $user->isOwner() || $user->autoApprovePosts()
            ? $request->boolean('featured')
            : false;

        $data['meta_keywords']    = $request->meta_keywords ?: $this->generateKeywords($request);
        $data['meta_description'] = $request->meta_description
            ?: Str::limit($this->extractPlainText($data['content']), 160);

        // Author sem auto_approve tentando publicar:
        // → status vira private + pending_review = true
        if (! $user->isOwner() && ! $user->autoApprovePosts() && $data['status'] === 'published') {
            $data['status']         = 'private';
            $data['pending_review'] = true;
        }

        $post->update($data);
        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        // Notifica owners apenas se entrou em pending_review agora
        if ($data['pending_review']) {
            $owners = User::where('role', 'owner')->get();
            foreach ($owners as $owner) {
                $owner->notify(new PostPendingNotification($post));
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    // ─── Aprovação de posts (somente owner) ──────────────────────────────────

    public function approvePost(Post $post)
    {
        $this->authorize('approve', $post);

        $post->update([
            'status'         => 'published',
            'pending_review' => false,
        ]);

        $post->author->notify(new PostApprovedNotification($post));

        return redirect()->back()->with('success', 'Post aprovado e publicado!');
    }

    public function rejectPost(Request $request, Post $post)
    {
        $this->authorize('reject', $post);

        $reason = $request->filled('reason') ? trim($request->reason) : null;

        $post->update([
            'status'         => 'private',
            'pending_review' => false,
        ]);

        $post->author->notify(new PostRejectedNotification($post, $reason));

        return redirect()->back()->with('success', 'Post rejeitado.');
    }

    public function pendingPosts()
    {
        $this->authorize('approve', new Post());

        $posts = Post::with(['author', 'category'])
            ->where('pending_review', true)
            ->latest()
            ->paginate(15);

        return view('dashboard.post.pending', [
            'pageTitle' => 'Posts Aguardando Aprovação',
            'posts'     => $posts,
        ]);
    }

    public function postDestroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post removido!');
    }

    public function postTrash()
    {
        $this->authorize('viewTrashed', Post::class);

        $posts = Post::onlyTrashed()
            ->with(['category' => fn($q) => $q->withTrashed()])
            ->latest('deleted_at')
            ->paginate(10);

        return view('dashboard.post.trash', [
            'pageTitle' => 'Lixeira — Posts',
            'posts'     => $posts,
        ]);
    }

    public function searchTags(Request $request)
    {
        $query = mb_strtoupper(trim($request->get('q', '')), 'UTF-8');

        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit(10)
            ->pluck('name');

        return response()->json($tags);
    }

    public function postDownloads(Post $post)
    {
        $this->authorize('view', $post);

        return view('dashboard.post.downloads', [
            'pageTitle' => 'Downloads — ' . $post->title,
            'post'      => $post,
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function sanitizeContent(string $html): string
    {
        $html = preg_replace('/<select[^>]*class="ql-ui"[^>]*>.*?<\/select>/is', '', $html);

        return $html;
    }

    private function extractPlainText(string $html): string
    {
        $text = preg_replace('/<select[^>]*>.*?<\/select>/is', '', $html);
        $text = preg_replace('/<(script|style)[^>]*>.*?<\/(script|style)>/is', '', $text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    private function generateKeywords(Request $request): string
    {
        $parts    = [];
        $category = Category::with('parentCategory')->find($request->category_id);

        if ($category) {
            $parts[] = $category->name;
            if ($category->parentCategory) {
                $parts[] = $category->parentCategory->name;
            }
        }

        if ($request->tags) {
            foreach (explode(',', $request->tags) as $tag) {
                $trimmed = trim($tag);
                if ($trimmed !== '') $parts[] = $trimmed;
            }
        }

        return implode(', ', array_unique($parts));
    }

    private function resolveTagIds(string $tagsString): array
    {
        $tagIds = [];

        foreach (explode(',', $tagsString) as $tagName) {
            $tagName = mb_strtoupper(trim($tagName), 'UTF-8');
            if ($tagName === '') continue;

            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }

    private function buildCategoriesHtml(?int $selectedId = null): string
    {
        $html = '<option value="">-- Selecione uma Categoria --</option>';

        $parentCategories = ParentCategory::with(['categories' => function ($q) {
            $q->where('status', true)->orderBy('name');
        }])->orderBy('name')->get();

        foreach ($parentCategories as $parent) {
            if ($parent->categories->isEmpty()) continue;
            $html .= '<optgroup label="' . e($parent->name) . '">';
            foreach ($parent->categories as $category) {
                $selected = $selectedId === $category->id ? ' selected' : '';
                $html .= '<option value="' . $category->id . '"' . $selected . '>' . e($category->name) . '</option>';
            }
            $html .= '</optgroup>';
        }

        $orphans = Category::whereNull('parent_category_id')
            ->where('status', true)->orderBy('name')->get();

        foreach ($orphans as $category) {
            $selected = $selectedId === $category->id ? ' selected' : '';
            $html .= '<option value="' . $category->id . '"' . $selected . '>' . e($category->name) . '</option>';
        }

        return $html;
    }
}