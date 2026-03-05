<?php

namespace App\View\Composers;

use App\Services\SidebarService;
use Illuminate\View\View;

/**
 * Injeta os widgets da sidebar em todas as views do front-end.
 *
 * Registrado no AppServiceProvider:
 *   View::composer(['layouts.app', 'layouts.front'], SidebarViewComposer::class);
 *
 * Ou em todas as views:
 *   View::composer('*', SidebarViewComposer::class);
 */
class SidebarViewComposer
{
    public function __construct(
        private readonly SidebarService $sidebarService
    ) {}

    public function compose(View $view): void
    {
        $view->with('sidebarWidgets', $this->sidebarService->getRenderedWidgets());
    }
}