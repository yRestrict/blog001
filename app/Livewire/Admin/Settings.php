<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class Settings extends Component
{
    use WithFileUploads;

    public string $tab = 'general_settings';

    // General Settings
    public $site_title;
    public $site_description;
    public $site_email;
    public $site_phone;
    public $site_meta_keywords;
    public $site_meta_description;

    // Logo & Favicon
    public $site_logo_light;
    public $site_logo_dark;
    public $site_favicon;

    // Temporary uploads
    public $new_logo_light;
    public $new_logo_dark;
    public $new_favicon;

    public $site_social_links = []; 

    protected $queryString = [
        'tab' => ['keep' => true],
    ];

    public function mount()
    {
        $settings = Setting::first();

        if ($settings) {
            $this->site_title = $settings->site_title;
            $this->site_description = $settings->site_description;
            $this->site_email = $settings->site_email;
            $this->site_phone = $settings->site_phone;
            $this->site_meta_keywords = $settings->site_meta_keywords;
            $this->site_meta_description = $settings->site_meta_description;
            $this->site_logo_light = $settings->site_logo_light;
            $this->site_logo_dark = $settings->site_logo_dark;
            $this->site_favicon = $settings->site_favicon;
            $this->site_social_links = $settings->site_social_links ?? [
                'facebook_url' => '',
                'twitter_url' => '',
                'instagram_url' => '',
                'linkedin_url' => '',
                'youtube_url' => ''
            ];
        }
    }

    public function selectTab(string $tab)
    {
        abort_unless(in_array($tab, [
            'general_settings',
            'logo_favicon',
            "social_link"
        ]), 404);

        $this->tab = $tab;
    }

    public function updateSocialLinks()
    {
        $this->validate([
            'site_social_links.*' => 'nullable|url',
        ], [
            'site_social_links.*.url' => 'Insira um link válido (ex: https://...)',
        ]);

        Setting::updateOrCreate(
            ['id' => 1],
            ['site_social_links' => $this->site_social_links]
        );

        Setting::clearCache();
        $this->dispatch('showToastr', type: 'success', message: __('messages.social_links_updated'));
    }

    public function updateGeneralSettings()
    {
        $this->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_meta_keywords' => 'nullable|string|max:500',
            'site_meta_description' => 'nullable|string|max:500',
        ]);

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_title' => $this->site_title,
                'site_description' => $this->site_description,
                'site_email' => $this->site_email,
                'site_phone' => $this->site_phone,
                'site_meta_keywords' => $this->site_meta_keywords,
                'site_meta_description' => $this->site_meta_description,
            ]
        );

        // ✅ Limpa cache direto do Model
        Setting::clearCache();

        $this->dispatch('showToastr', type: 'success', message: __('messages.general_settings_updated'));
    }

    public function updateLogoFavicon()
    {
        $this->validate([
            'new_logo_light' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,svg',
            'new_logo_dark' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,svg',
            'new_favicon' => 'nullable|image|max:1024|mimes:png,ico,jpg,jpeg',
        ], [
            'new_logo_light.max' => 'Logo light deve ter no máximo 2MB',
            'new_logo_dark.max' => 'Logo dark deve ter no máximo 2MB',
            'new_favicon.max' => 'Favicon deve ter no máximo 1MB',
        ]);

        $settings = Setting::firstOrCreate(['id' => 1]);

        // Upload Logo Light
        if ($this->new_logo_light) {
            if ($settings->site_logo_light && File::exists(public_path('uploads/logo/' . $settings->site_logo_light))) {
                File::delete(public_path('uploads/logo/' . $settings->site_logo_light));
            }

            $filename = 'logo_light_' . time() . '.' . $this->new_logo_light->extension();
            $this->new_logo_light->storeAs('uploads/logo', $filename, 'public_uploads');
            $settings->site_logo_light = $filename;
            $this->site_logo_light = $filename;
        }

        // Upload Logo Dark
        if ($this->new_logo_dark) {
            if ($settings->site_logo_dark && File::exists(public_path('uploads/logo/' . $settings->site_logo_dark))) {
                File::delete(public_path('uploads/logo/' . $settings->site_logo_dark));
            }

            $filename = 'logo_dark_' . time() . '.' . $this->new_logo_dark->extension();
            $this->new_logo_dark->storeAs('uploads/logo', $filename, 'public_uploads');
            $settings->site_logo_dark = $filename;
            $this->site_logo_dark = $filename;
        }

        // Upload Favicon
        if ($this->new_favicon) {
            if ($settings->site_favicon && File::exists(public_path('uploads/logo/' . $settings->site_favicon))) {
                File::delete(public_path('uploads/logo/' . $settings->site_favicon));


            }

            $filename = 'favicon_' . time() . '.' . $this->new_favicon->extension();
            $this->new_favicon->storeAs('uploads/logo', $filename, 'public_uploads'); // ← disco correto?
            $settings->site_favicon = $filename;
            $this->site_favicon = $filename;
        }

        $settings->save();

        // ✅ Limpa cache direto do Model
        Setting::clearCache();

        $this->reset(['new_logo_light', 'new_logo_dark', 'new_favicon']);

        $this->dispatch('showToastr', type: 'success', message: __('messages.logo_favicon_updated'));
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}