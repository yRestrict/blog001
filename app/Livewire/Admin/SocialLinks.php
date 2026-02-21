<?php

namespace App\Livewire\Admin;

use App\Models\UserSocialLink;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SocialLinks extends Component
{
    // Social Links
    public ?string $facebook_username = null;
    public ?string $instagram_username = null;
    public ?string $youtube_channel = null;
    public ?string $whatsapp_number = null;
    public ?string $twitter_username = null;
    public ?string $steam_username = null;

    public function mount()
    {
        $socialLinks = Auth::user()->socialLinks;

        if ($socialLinks) {
            $this->facebook_username = $this->extractUsername($socialLinks->facebook_url, 'facebook');
            $this->instagram_username = $this->extractUsername($socialLinks->instagram_url, 'instagram');
            $this->youtube_channel = $this->extractUsername($socialLinks->youtube_url, 'youtube');
            $this->whatsapp_number = $this->extractWhatsAppNumber($socialLinks->whatsapp_url);
            $this->twitter_username = $this->extractUsername($socialLinks->twitter_url, 'twitter');
            $this->steam_username = $this->extractUsername($socialLinks->steam_url, 'steam');
        }
    }

    public function updateSocialLinks()
    {
        $this->validate([
            'facebook_username' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9._]+$/',
            'instagram_username' => 'nullable|string|max:30|regex:/^[a-zA-Z0-9._]+$/',
            'youtube_channel' => 'nullable|string|max:100',
            'whatsapp_number' => 'nullable|string|max:20|regex:/^[0-9+\s\-()]+$/',
            'twitter_username' => 'nullable|string|max:15|regex:/^[a-zA-Z0-9_]+$/',
            'steam_username' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_]+$/',
        ], [
            'facebook_username.regex' => 'Use apenas letras, números, pontos e underline',
            'instagram_username.regex' => 'Use apenas letras, números, pontos e underline',
            'whatsapp_number.regex' => 'Digite apenas números (ex: 5511999999999)',
            'twitter_username.regex' => 'Use apenas letras, números e underline',
            'steam_username.regex' => 'Use apenas letras, números e underline',
        ]);

        // Converte usernames/números em URLs completas
        $data = [
            'facebook_url' => $this->formatFacebookUrl($this->facebook_username),
            'instagram_url' => $this->formatInstagramUrl($this->instagram_username),
            'youtube_url' => $this->formatYoutubeUrl($this->youtube_channel),
            'whatsapp_url' => $this->formatWhatsAppUrl($this->whatsapp_number),
            'twitter_url' => $this->formatTwitterUrl($this->twitter_username),
            'steam_url' => $this->formatSteamUrl($this->steam_username),
        ];

        UserSocialLink::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        Auth::user()->load('socialLinks');

        $this->dispatch('showToastr', type: 'success', message: 'Redes sociais atualizadas com sucesso!');
        $this->dispatch('$refresh');
    }

    // ==================== FORMATADORES DE URL ====================

    private function formatFacebookUrl(?string $username): ?string
    {
        if (!$username) return null;
        
        // Remove @ se tiver
        $username = ltrim($username, '@');
        
        return 'https://facebook.com/' . $username;
    }

    private function formatInstagramUrl(?string $username): ?string
    {
        if (!$username) return null;
        
        $username = ltrim($username, '@');
        
        return 'https://instagram.com/' . $username;
    }

    private function formatYoutubeUrl(?string $channel): ?string
    {
        if (!$channel) return null;
        
        // Se já tiver @, usa como está
        if (str_starts_with($channel, '@')) {
            return 'https://youtube.com/' . $channel;
        }
        
        // Se for um ID de canal (UC...), usa /channel/
        if (str_starts_with($channel, 'UC')) {
            return 'https://youtube.com/channel/' . $channel;
        }
        
        // Senão, assume que é um username e adiciona @
        return 'https://youtube.com/@' . $channel;
    }

    private function formatWhatsAppUrl(?string $number): ?string
    {
        if (!$number) return null;
        
        // Remove tudo que não é número
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);
        
        // Adiciona código do Brasil (55) se necessário
        if (strlen($cleanNumber) == 11 && !str_starts_with($cleanNumber, '55')) {
            $cleanNumber = '55' . $cleanNumber;
        }
        
        return 'https://wa.me/' . $cleanNumber;
    }

    private function formatTwitterUrl(?string $username): ?string
    {
        if (!$username) return null;
        
        $username = ltrim($username, '@');
        
        return 'https://twitter.com/' . $username;
    }

    private function formatSteamUrl(?string $username): ?string
    {
        if (!$username) return null;
        
        return 'https://steamcommunity.com/id/' . $username;
    }

    // ==================== EXTRATORES DE USERNAME ====================

    private function extractUsername(?string $url, string $platform): ?string
    {
        if (!$url) return null;
        
        // Se não for URL, retorna como está (já é username)
        if (!str_contains($url, 'http')) {
            return ltrim($url, '@');
        }
        
        // Extrai username da URL
        $patterns = [
            'facebook' => '/facebook\.com\/([^\/\?]+)/',
            'instagram' => '/instagram\.com\/([^\/\?]+)/',
            'youtube' => '/youtube\.com\/([@][^\/\?]+|channel\/[^\/\?]+)/',
            'twitter' => '/twitter\.com\/([^\/\?]+)/',
            'steam' => '/steamcommunity\.com\/id\/([^\/\?]+)/',
        ];
        
        if (isset($patterns[$platform]) && preg_match($patterns[$platform], $url, $matches)) {
            return ltrim($matches[1], '@');
        }
        
        return null;
    }

    private function extractWhatsAppNumber(?string $url): ?string
    {
        if (!$url) return null;
        
        // Se já for um número, retorna
        if (!str_contains($url, 'http')) {
            return $url;
        }
        
        // Extrai número da URL: https://wa.me/5511999999999
        if (preg_match('/wa\.me\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Extrai de api.whatsapp.com/send?phone=5511999999999
        if (preg_match('/phone=(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    public function render()
    {
        return view('livewire.admin.social-links');
    }
}