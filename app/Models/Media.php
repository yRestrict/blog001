<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'uploaded_by',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'extension',
        'size',
        'downloads',
    ];

    protected $casts = [
        'size'      => 'integer',
        'downloads' => 'integer',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────────

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * URL pública para download.
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('media.download', $this->id);
    }

    /**
     * Tamanho formatado (ex: 2.4 MB, 512 KB).
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2)    . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2)       . ' KB';

        return $bytes . ' B';
    }

    /**
     * Ícone Font Awesome baseado na extensão.
     */
    public function getIconAttribute(): string
    {
        return match (strtolower($this->extension)) {
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => 'fa-file-image-o',
            'pdf'                                        => 'fa-file-pdf-o',
            'doc', 'docx'                               => 'fa-file-word-o',
            'txt'                                        => 'fa-file-text-o',
            'zip', 'rar', 'tar', '7z', 'gz'             => 'fa-file-archive-o',
            'mp3', 'wav', 'ogg', 'flac'                 => 'fa-file-audio-o',
            'mp4', 'mkv', 'avi', 'mov', 'webm'          => 'fa-file-video-o',
            'amxx', 'sma'                                => 'fa-file-code-o',
            'bsp', 'model', 'sprites'                   => 'fa-cube',
            default                                      => 'fa-file-o',
        };
    }

    /**
     * Cor do ícone por categoria.
     */
    public function getIconColorAttribute(): string
    {
        return match (strtolower($this->extension)) {
            'jpg', 'jpeg', 'png', 'gif', 'webp' => '#10b981',
            'pdf'                                => '#ef4444',
            'doc', 'docx'                        => '#3b82f6',
            'zip', 'rar', 'tar', '7z'            => '#f59e0b',
            'mp3', 'wav', 'ogg'                  => '#8b5cf6',
            'mp4', 'mkv', 'avi'                  => '#ec4899',
            'amxx', 'sma', 'bsp'                 => '#6366f1',
            default                              => '#6d7279',
        };
    }
}