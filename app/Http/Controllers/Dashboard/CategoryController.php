<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function categoriesPage(Request $request)
    {
        $data = [
            'pageTitle' => 'Categorias',
        ];
        return view('dashboard.category.index', $data);
    }

    public function categoriesTrash()
    {
        $this->authorize('viewAny', Category::class);

        return view('dashboard.category.trash', [
            'pageTitle' => 'Lixeira - Categorias',
        ]);
    }
}
