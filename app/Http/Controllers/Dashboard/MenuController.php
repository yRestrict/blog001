<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Página do menu Header no painel admin.
     * O componente Livewire <livewire:admin.menus type="header" />
     * faz toda a gestão de dados — o controller só renderiza a view.
     */
    public function headerMenu()
    {
        $data = ['pageTitle' => 'Menu do Header'];
        return view('dashboard.setting.menus.header', $data);
    }

    /**
     * Página do menu Footer no painel admin.
     */
    public function footerMenu()
    {
        $data = ['pageTitle' => 'Menu do Footer'];
        return view('dashboard.setting.menus.footer', $data);
    }
}