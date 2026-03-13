<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\Tag;
use App\Notifications\PostApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function PostPage()
    {
        return view('dashboard.post.index', [
            'pageTitle'    => 'Posts',
            'pendingCount' => Post::where('status', 'pending_review')->count(),
        ]);
    }

    public function postCreate()
    {
        return view('dashboard.post.create', [
            'pageTitle'      => 'Criar Post',
            'categorieshtml' => $this->buildCategoriesHtml(),
        ]);
    }

    public function postStore(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'required|exists:categories,id',
            'tags'             => 'nullable|string',
            'thumbnail'        => 'nullable|image|max:2048',
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

        $data['author_id']        = $user->id;
        $data['featured']         = $request->boolean('featured');
        $data['comment']          = $request->boolean('comment');
        $data['meta_keywords']    = $request->meta_keywords ?: $this->generateKeywords($request);
        $data['meta_description'] = $request->meta_description
            ?: Str::limit(preg_replace('/\s+/', ' ', strip_tags($request->content)), 160);

        // Se o autor não é owner e não tem auto_approve, manda para revisão
        if (! $user->isOwner() && ! $user->autoApprovePosts() && $data['status'] === 'published') {
            $data['status'] = 'pending_review';
        }

        $post = Post::create($data);
        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        $message = $data['status'] === 'pending_review'
            ? 'Post enviado para aprovação!'
            : 'Post criado com sucesso!';

        return redirect()->route('admin.posts.index')->with('success', $message);
    }

    public function postEdit(Post $post)
    {
        return view('dashboard.post.edit', [
            'pageTitle'      => 'Editar Post',
            'post'           => $post->load('tags'),
            'categorieshtml' => $this->buildCategoriesHtml($post->category_id),
            'currentTags'    => $post->tags->pluck('name')->implode(', '),
        ]);
    }

    public function postUpdate(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'tags'             => 'nullable|string',
            'thumbnail'        => 'nullable|image|max:2048',
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

        $data['featured']         = $request->boolean('featured');
        $data['comment']          = $request->boolean('comment');
        $data['meta_keywords']    = $request->meta_keywords ?: $this->generateKeywords($request);
        $data['meta_description'] = $request->meta_description
            ?: Str::limit(preg_replace('/\s+/', ' ', strip_tags($request->content)), 160);

        // Se author não tem auto_approve e tenta publicar, manda para revisão
        if (! $user->isOwner() && ! $user->autoApprovePosts() && $data['status'] === 'published') {
            $data['status'] = 'pending_review';
        }

        $post->update($data);
        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        return redirect()->route('admin.posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    // ─── Aprovação de posts (somente owner) ──────────────────────────────────

    public function approvePost(Post $post)
    {
        abort_unless(Auth::user()->isOwner(), 403);

        $post->update(['status' => 'published']);

        // Notifica o author
        $post->author->notify(new PostApprovedNotification($post));

        return redirect()->back()->with('success', 'Post aprovado e publicado!');
    }

    public function rejectPost(Request $request, Post $post)
    {
        abort_unless(Auth::user()->isOwner(), 403);

        $post->update(['status' => 'draft']);

        return redirect()->back()->with('success', 'Post rejeitado e movido para rascunho.');
    }

    public function pendingPosts()
    {
        abort_unless(Auth::user()->isOwner(), 403);

        $posts = Post::with(['author', 'category'])
            ->where('status', 'pending_review')
            ->latest()
            ->paginate(15);

        return view('dashboard.post.pending', [
            'pageTitle' => 'Posts Aguardando Aprovação',
            'posts'     => $posts,
        ]);
    }

    public function postDestroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post removido!');
    }

    public function postTrash()
    {
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

    // ─── Helpers ─────────────────────────────────────────────────────────────

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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);
    
        $file     = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/posts'), $filename);
    
        return response()->json([
            'url' => asset('uploads/posts/' . $filename),
        ]);
    }
}