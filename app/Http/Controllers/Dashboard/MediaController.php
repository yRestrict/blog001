<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Media;

class MediaController extends Controller
{
    /**
     * Página de mídia no dashboard (somente owner).
     */
    public function index()
    {
        return view('dashboard.media.index', [
            'pageTitle' => 'Mídia',
        ]);
    }

    /**
     * Download público — qualquer visitante pode baixar.
     * Incrementa o contador de downloads.
     */
    public function download(Media $media)
    {
        $path = storage_path('app/public/' . $media->path);

        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $media->increment('downloads');

        return response()->download($path, $media->original_name);
    }
}