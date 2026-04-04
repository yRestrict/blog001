<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaLibrary extends Component
{
    use WithFileUploads, WithPagination;

    // ── Upload ────────────────────────────────────────────────────────────────
    public $uploadedFiles = [];
    public bool $showUploadModal = false;
    public bool $uploading       = false;

    // ── Busca / filtro ────────────────────────────────────────────────────────
    public string $search    = '';
    public string $filterExt = '';

    // ── Confirmação de delete ─────────────────────────────────────────────────
    public ?int $confirmDeleteId = null;

    const ALLOWED_EXTENSIONS = [
        'jpg','jpeg','png','gif','webp','svg',
        'pdf','doc','docx','txt',
        'zip','rar','tar','7z','gz',
        'mp3','wav','ogg','flac',
        'mp4','mkv','avi','mov','webm',
        'amxx','sma','bsp','model','sprites',
    ];

    protected $queryString = ['search', 'filterExt'];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterExt(): void { $this->resetPage(); }

    // ── Helpers de permissão ──────────────────────────────────────────────────

    private function canUploadOrDelete(): bool
    {
        $user = Auth::user();
        return $user->isOwner() || $user->autoApprovePosts();
    }

    // ── Upload ────────────────────────────────────────────────────────────────

    public function openUpload(): void
    {
        if (! $this->canUploadOrDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para enviar arquivos.');
            return;
        }

        $this->uploadedFiles   = [];
        $this->showUploadModal = true;
    }

    public function saveUpload(): void
    {
        if (! $this->canUploadOrDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para enviar arquivos.');
            return;
        }

        $this->validate([
            'uploadedFiles.*' => 'file|max:204800',
        ], [
            'uploadedFiles.*.file' => 'Arquivo inválido.',
            'uploadedFiles.*.max'  => 'Tamanho máximo: 200MB.',
        ]);

        foreach ($this->uploadedFiles as $file) {
            $ext          = strtolower($file->getClientOriginalExtension());
            $originalName = $file->getClientOriginalName();
            $storedName   = Str::uuid() . '.' . $ext;
            $path         = $file->storeAs('media', $storedName, 'public');

            Media::create([
                'uploaded_by'   => Auth::id(),
                'original_name' => $originalName,
                'stored_name'   => $storedName,
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'extension'     => $ext,
                'size'          => $file->getSize(),
            ]);
        }

        $this->uploadedFiles   = [];
        $this->showUploadModal = false;
        $this->dispatch('notify', type: 'success', message: 'Arquivo(s) enviado(s) com sucesso!');
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        if (! $this->canUploadOrDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para remover arquivos.');
            return;
        }

        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function delete(int $id): void
    {
        if (! $this->canUploadOrDelete()) {
            $this->dispatch('notify', type: 'error', message: 'Você não tem permissão para remover arquivos.');
            return;
        }

        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->path);
        $media->delete();

        $this->confirmDeleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Arquivo removido.');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $query = Media::with('uploader')
            ->when($this->search, fn($q) =>
                $q->where('original_name', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterExt, fn($q) =>
                $q->where('extension', $this->filterExt)
            )
            ->latest();

        $files = $query->paginate(15);

        $extensions = Media::select('extension')
            ->distinct()
            ->orderBy('extension')
            ->pluck('extension');

        return view('livewire.admin.media-library', [
            'files'              => $files,
            'extensions'         => $extensions,
            'canUploadOrDelete'  => $this->canUploadOrDelete(),
        ]);
    }
}