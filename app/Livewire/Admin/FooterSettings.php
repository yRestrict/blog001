<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class FooterSettings extends Component
{
    public $footer_category_order = 'posts';

    public function mount()
    {
        $settings = Setting::first();
        if ($settings) {
            $this->footer_category_order = $settings->footer_category_order ?? 'posts';
        }
    }

    public function save()
    {
        $this->validate([
            'footer_category_order' => 'required|in:posts,views',
        ]);

        Setting::updateOrCreate(
            ['id' => 1],
            ['footer_category_order' => $this->footer_category_order]
        );

        Setting::clearCache();

        $this->dispatch('showToastr', type: 'success', message: 'Configurações do footer salvas!');
    }

    public function render()
    {
        return view('livewire.admin.footer-settings');
    }
}