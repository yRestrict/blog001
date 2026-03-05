<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function PostPage()
    {
        return view('dashboard.post.index', [
            'pageTitle' => 'Posts',
        ]);
    }

    public function postCreate()
    {
        $categorieshtml = $this->buildCategoriesHtml();

        return view('dashboard.post.create', [
            'pageTitle'      => 'Criar Post',
            'categorieshtml' => $categorieshtml,
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

        $data['author_id'] = Auth::id();
        $data['featured']  = $request->boolean('featured');
        $data['comment']   = $request->boolean('comment');

        $post = Post::create($data);

        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        return redirect()->route('admin.posts.index')->with('success', 'Post criado com sucesso!');
    }

    public function postEdit(Post $post)
    {
        $categorieshtml = $this->buildCategoriesHtml($post->category_id);

        // Monta string de tags já associadas para preencher o campo
        $currentTags = $post->tags->pluck('name')->implode(', ');

        return view('dashboard.post.edit', [
            'pageTitle'      => 'Editar Post',
            'post'           => $post->load('tags'),
            'categorieshtml' => $categorieshtml,
            'currentTags'    => $currentTags,
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

        $data['featured'] = $request->boolean('featured');
        $data['comment']  = $request->boolean('comment');

        $post->update($data);
        $post->tags()->sync($this->resolveTagIds($data['tags'] ?? ''));

        return redirect()->route('admin.posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    public function postDestroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post removido!');
    }

    public function postTrash()
    {
        return view('dashboard.post.trash', [
            'pageTitle' => 'Lixeira — Posts',
        ]);
    }

    // ─── API: busca tags para autocomplete ───────────────────────────────────
    // Registre a rota: Route::get('/tags/search', [PostController::class, 'searchTags'])->name('tags.search');
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

    /**
     * Resolve IDs das tags a partir de uma string separada por vírgula.
     * Cria tags novas se não existirem. Sempre em maiúsculo.
     */
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
            ->where('status', true)
            ->orderBy('name')
            ->get();

        foreach ($orphans as $category) {
            $selected = $selectedId === $category->id ? ' selected' : '';
            $html .= '<option value="' . $category->id . '"' . $selected . '>' . e($category->name) . '</option>';
        }

        return $html;
    }
}