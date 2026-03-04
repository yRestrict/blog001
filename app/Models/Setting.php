<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_title',
        'site_description',
        'site_email',
        'site_phone',
        'site_meta_keywords',
        'site_meta_description',
        'site_logo_light',
        'site_logo_dark',
        'site_favicon',
        'site_social_links',
        'footer_category_order', // 'posts' ou 'views'
    ];

    protected $casts = [
        'site_social_links' => 'array',
    ];

    // ==================== MÉTODOS ESTÁTICOS ====================

    public static function getSettings()
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::first();
        });
    }

    public static function clearCache()
    {
        Cache::forget('site_settings');
    }

    public static function get($key, $default = null)
    {
        $settings = self::getSettings();
        return $settings->$key ?? $default;
    }

    // ==================== ATALHOS ÚTEIS ====================

    public static function title()
    {
        return self::get('site_title', config('app.name'));
    }

    public static function email()
    {
        return self::get('site_email');
    }

    public static function phone()
    {
        return self::get('site_phone');
    }

    public static function logoLight()
    {
        $logo = self::get('site_logo_light');
        if ($logo && file_exists(public_path('uploads/logo/' . $logo))) {
            return asset('uploads/logo/' . $logo);
        }
        return false;
    }

    public static function logoDark()
    {
        $logo = self::get('site_logo_dark');
        if ($logo && file_exists(public_path('uploads/logo/' . $logo))) {
            return asset('uploads/logo/' . $logo);
        }
        return false;
    }

    public static function favicon()
    {
        $favicon = self::get('site_favicon');
        if ($favicon && file_exists(public_path('uploads/logo/' . $favicon))) {
            return asset('uploads/logo/' . $favicon);
        }
        return false;
    }

    public static function metaKeywords()
    {
        return self::get('site_meta_keywords');
    }

    public static function description()
    {
        return self::get('site_description');
    }

    public static function metaDescription()
    {
        return self::get('site_meta_description');
    }

    public static function footerCategoryOrder()
    {
        return self::get('footer_category_order', 'posts');
    }
}