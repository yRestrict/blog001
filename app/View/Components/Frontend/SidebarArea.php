<?php

namespace App\View\Components\Frontend;

use App\Models\Sidebar;
use Illuminate\View\Component;

/**
 * BLADE COMPONENT: SidebarArea
 *
 * Este é o componente que você chama nas suas views de frontend.
 * Ele busca a sidebar pelo slug e passa os dados para a view.
 *
 * Uso nas views:
 *   <x-sidebar-area slug="main-sidebar" />
 *   <x-sidebar-area slug="post-sidebar" />
 *
 * O componente se registra automaticamente se você seguir a convenção:
 *   Arquivo: app/View/Components/Frontend/SidebarArea.php
 *   Tag:     <x-frontend.sidebar-area slug="..." />
 *
 * Ou registre manualmente no AppServiceProvider:
 *   Blade::component('sidebar-area', SidebarArea::class);
 */
class SidebarArea extends Component
{
    /**
     * A sidebar encontrada no banco (ou null se não existir/inativa).
     * A propriedade pública é automaticamente passada para a view do componente.
     */
    public ?Sidebar $sidebar;

    /**
     * O construtor recebe o slug via atributo na tag do componente.
     *
     * @param string $slug  O slug da sidebar (ex: 'main-sidebar')
     */
    public function __construct(string $slug)
    {
        // Usa o método do model com cache — não faz query toda requisição
        $this->sidebar = Sidebar::getBySlug($slug);
    }

    /**
     * Define qual view Blade renderiza este componente.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.frontend.sidebar-area');
    }
}