<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\PostDownload;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PostDownloads extends Component
{
    use WithFileUploads;

    public ?int  $postId     = null;
    public bool  $showModal  = false;
    public array $buttons    = [];
    public ?int  $openIndex  = null; // índice do accordion aberto

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->buttons as $i => $btn) {
            $rules["buttons.{$i}.label"]        = 'required|string|max:100';
            $rules["buttons.{$i}.url"]          = 'nullable|url|max:500';
            $rules["buttons.{$i}.position"]     = 'required|in:left,center,right,block';
            $rules["buttons.{$i}.uploadedFile"] = 'nullable|file|max:102400';
        }
        return $rules;
    }

    protected $messages = [
        'buttons.*.label.required'   => 'O nome é obrigatório.',
        'buttons.*.url.url'          => 'Informe uma URL válida.',
        'buttons.*.uploadedFile.max' => 'Máximo 100MB.',
    ];

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->postId = $post->id;
            $this->loadFromDb();
        }
    }

    public function openModal(): void
    {
        if ($this->postId) {
            $this->loadFromDb();
        }
        // Abre o primeiro item por padrão
        $this->openIndex = count($this->buttons) > 0 ? 0 : null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function loadFromDb(): void
    {
        $this->buttons = PostDownload::where('post_id', $this->postId)
            ->orderBy('order')
            ->get()
            ->map(fn($d) => [
                'id'           => $d->id,
                'label'        => $d->label,
                'url'          => $d->url ?? '',
                'existingFile' => $d->file,
                'uploadedFile' => null,
                'position'     => $d->position,
            ])->toArray();
    }

    public function toggleOpen(int $index): void
    {
        $this->openIndex = ($this->openIndex === $index) ? null : $index;
    }

    public function addButton(): void
    {
        $this->buttons[] = [
            'id'           => null,
            'label'        => '',
            'url'          => '',
            'existingFile' => null,
            'uploadedFile' => null,
            'position'     => 'block',
        ];
        // Abre o novo botão automaticamente e fecha os outros
        $this->openIndex = count($this->buttons) - 1;
    }

    public function removeButton(int $index): void
    {
        $btn = $this->buttons[$index];

        if ($this->postId && !empty($btn['id'])) {
            $record = PostDownload::find($btn['id']);
            if ($record) {
                if ($record->file) Storage::disk('public')->delete($record->file);
                $record->delete();
            }
        } elseif (!empty($btn['existingFile'])) {
            Storage::disk('public')->delete($btn['existingFile']);
        }

        array_splice($this->buttons, $index, 1);
        $this->syncOrder();

        // Ajusta o índice aberto após remoção
        if ($this->openIndex === $index) {
            $this->openIndex = count($this->buttons) > 0 ? max(0, $index - 1) : null;
        } elseif ($this->openIndex > $index) {
            $this->openIndex--;
        }
    }

    public function removeFile(int $index): void
    {
        $file = $this->buttons[$index]['existingFile'] ?? null;
        if ($file) {
            Storage::disk('public')->delete($file);
            if (!empty($this->buttons[$index]['id'])) {
                PostDownload::where('id', $this->buttons[$index]['id'])->update(['file' => null]);
            }
        }
        $this->buttons[$index]['existingFile'] = null;
    }

    public function save(): void
    {
        if (empty($this->buttons)) {
            $this->showModal = false;
            return;
        }

        $this->validate();

        if (!$this->postId) {
            // Post ainda não foi salvo — fecha o modal
            // Os botões serão persistidos após o postStore via syncDownloads()
            $this->showModal = false;
            return;
        }

        foreach ($this->buttons as $i => $btn) {
            $filePath = $btn['existingFile'] ?? null;

            if (!empty($btn['uploadedFile'])) {
                if ($filePath) Storage::disk('public')->delete($filePath);
                $filePath = $btn['uploadedFile']->store('downloads', 'public');
            }

            $data = [
                'post_id'  => $this->postId,
                'label'    => $btn['label'],
                'url'      => $btn['url'] ?: null,
                'file'     => $filePath,
                'position' => $btn['position'],
                'order'    => $i + 1,
            ];

            if (!empty($btn['id'])) {
                PostDownload::findOrFail($btn['id'])->update($data);
            } else {
                $created = PostDownload::create($data);
                $this->buttons[$i]['id']           = $created->id;
                $this->buttons[$i]['existingFile'] = $filePath;
                $this->buttons[$i]['uploadedFile'] = null;
            }
        }

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Botões de download salvos!');
        $this->loadFromDb();
    }

    private function syncOrder(): void
    {
        foreach ($this->buttons as $i => $btn) {
            if ($this->postId && !empty($btn['id'])) {
                PostDownload::where('id', $btn['id'])->update(['order' => $i + 1]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.post-downloads');
    }
}