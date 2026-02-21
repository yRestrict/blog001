<?php

namespace App\View\Components;

use App\Models\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public function render(): View|Closure|string
    {
        $menu = Menu::where('type', 'footer')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function ($query) {
                $query->where('is_active', true)
                      ->orderBy('order')
                      ->with(['children' => function ($q) {
                          $q->where('is_active', true)
                            ->orderBy('order');
                      }]);
            }])
            ->orderBy('order')
            ->get();

        return view('components.footer', [
            'menu' => $menu,
        ]);
    }
}
