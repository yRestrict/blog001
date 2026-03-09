<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'label',
        'url',
        'file',
        'position',
        'order',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────────

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // ─── Helper: retorna a URL final do download ──────────────────────────────
    // Prioriza URL externa; se não tiver, retorna o arquivo do storage
    public function getDownloadUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }

        return $this->file ? asset('storage/' . $this->file) : '#';
    }

    // ─── Helper: CSS de alinhamento ───────────────────────────────────────────
    public function getAlignmentClassAttribute(): string
    {
        return match ($this->position) {
            'center' => 'text-center',
            'right'  => 'text-right',
            'block'  => 'd-block',
            default  => 'text-left',
        };
    }

    public function download(PostDownload $download)
    {
        // Incrementa o contador de downloads no post
        $download->post->increment('downloads');

        // Se tem arquivo no storage, faz download direto
        if ($download->file) {
            return response()->download(storage_path('app/public/' . $download->file));
        }

        // Se tem URL externa, redireciona
        if ($download->url) {
            return redirect()->away($download->url);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado.');
    }
}