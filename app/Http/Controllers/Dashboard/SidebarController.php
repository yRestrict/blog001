<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Sidebar;
use App\Http\Controllers\Controller;

/**
 * CONTROLLER: SidebarController
 *
 * Responsabilidade única: definir o título da página e retornar a view.
 * Toda a lógica de CRUD fica no componente Livewire SidebarManager.
 *
 * Rotas (routes/web.php):
 *   Route::get('/sidebars', [SidebarController::class, 'index'])
 *        ->name('dashboard.sidebars');
 *
 *   Route::get('/sidebars/{sidebar}/widgets', [SidebarController::class, 'widgets'])
 *        ->name('dashboard.sidebars.widgets');
 */
class SidebarController extends Controller
{
    /**
     * Página de listagem de sidebars.
     * A view chama @livewire('admin.sidebar.sidebar-manager')
     */
    public function index()
    {
        return view('dashboard.sidebar.index', [
            'pageTitle' => 'Gerenciar Sidebars',
        ]);
    }

    /**
     * Página de widgets de uma sidebar específica.
     * A view chama @livewire('admin.sidebar.widget-manager', ['sidebar' => $sidebar])
     *
     * O Laravel injeta $sidebar automaticamente via Route Model Binding.
     */
    public function widgets(Sidebar $sidebar)
    {
        return view('dashboard.sidebar.widgets', [
            'pageTitle' => 'Widgets — ' . $sidebar->name,
            'sidebar'   => $sidebar,
        ]);
    }
}