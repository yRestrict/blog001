<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;


class SidebarController extends Controller
{

    public function index()
    {
        return view('dashboard.sidebar.index', [
            'pageTitle' => 'Gerenciar Sidebars',
        ]);
    }

}