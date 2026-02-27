<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Página de listagem de tags (usa Livewire).
     */
    public function index()
    {
        return view('dashboard.tag.index', [
            'pageTitle' => 'Tags',
        ]);
    }
}