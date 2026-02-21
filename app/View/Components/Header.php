<?php

namespace App\View\Components;

use App\Models\Menu;
use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function render(): View|Closure|string
    {
        $siteSetting = Setting::first();

        $menu = Menu::where('type', 'header')
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

        return view('components.header', [
            'menu'        => $menu,
            'siteSetting' => $siteSetting,
        ]);
    }
}