<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
        return view('dashboard.comments.index', [
            'pageTitle' => 'Moderação de Comentários',
        ]);
    }
}