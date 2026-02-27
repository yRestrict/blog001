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
    /**
     * Listagem de posts.
     */
    public function PostPage()
    {
        return view('dashboard.post.index', [
            'pageTitle' => 'Posts',
        ]);
    }

    /**
     * Formulário de criação.
     */
    public function postCreate()
    {
        $categorieshtml = $this->buildCategoriesHtml();

        return view('dashboard.post.create', [
            'pageTitle'      => 'Criar Post',
            'categorieshtml' => $categorieshtml,
        ]);
    }

    /**
     * Salva novo post.
     */
    public function postStore(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'tags'             => 'nullable|string',
            'featured_image'   => 'nullable|image|max:2048',
            'featured'         => 'nullable|boolean',
            'comment'          => 'nullable|boolean',
            'status'           => 'required|in:draft,published,private',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Upload da imagem destacada
        if ($request->hasFile('featured_image')) {
            $filename = time() . '_' . $request->file('featured_image')->getClientOriginalName();
            $request->file('featured_image')->move(public_path('uploads/posts'), $filename);
            $data['featured_image'] = $filename;
        }

        $data['author_id'] = Auth::id();
        $data['featured']  = $request->boolean('featured');
        $data['comment']   = $request->boolean('comment');

        $post = Post::create($data);

        // Sincroniza tags (cria se não existir)
        if (!empty($data['tags'])) {
            $tagIds = [];
            foreach (explode(',', $data['tags']) as $tagName) {
                $tagName = trim($tagName);
                if ($tagName === '') continue;

                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post criado com sucesso!');
    }

    /**
     * Formulário de edição.
     */
    public function postEdit(Post $post)
    {
        $categorieshtml = $this->buildCategoriesHtml($post->category_id);

        return view('dashboard.post.edit', [
            'pageTitle'      => 'Editar Post',
            'post'           => $post->load('tags'),
            'categorieshtml' => $categorieshtml,
        ]);
    }

    /**
     * Atualiza post existente.
     */
    public function postUpdate(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'tags'             => 'nullable|string',
            'featured_image'   => 'nullable|image|max:2048',
            'featured'         => 'nullable|boolean',
            'comment'          => 'nullable|boolean',
            'status'           => 'required|in:draft,published,private',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('featured_image')) {
            // Remove imagem antiga
            if ($post->featured_image && file_exists(public_path('uploads/posts/' . $post->featured_image))) {
                unlink(public_path('uploads/posts/' . $post->featured_image));
            }

            $filename = time() . '_' . $request->file('featured_image')->getClientOriginalName();
            $request->file('featured_image')->move(public_path('uploads/posts'), $filename);
            $data['featured_image'] = $filename;
        }

        $data['featured'] = $request->boolean('featured');
        $data['comment']  = $request->boolean('comment');

        $post->update($data);

        // Sincroniza tags
        $tagIds = [];
        if (!empty($data['tags'])) {
            foreach (explode(',', $data['tags']) as $tagName) {
                $tagName = trim($tagName);
                if ($tagName === '') continue;

                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
        }
        $post->tags()->sync($tagIds);

        return redirect()->route('admin.posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Soft delete do post.
     */
    public function postDestroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post removido!');
    }

    /**
     * Página da lixeira de posts.
     */
    public function postTrash()
    {
        return view('dashboard.post.trash', [
            'pageTitle' => 'Lixeira — Posts',
        ]);
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    /**
     * Monta o HTML do <select> de categorias agrupadas por categoria pai.
     */
    private function buildCategoriesHtml(?int $selectedId = null): string
    {
        $html = '<option value="">-- Selecione uma Categoria --</option>';

        // Categorias agrupadas (com pai)
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

        // Categorias sem pai
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