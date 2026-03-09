<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostComments extends Component
{
    public Post $post;

    public string $guestName  = '';
    public string $guestEmail = '';
    public string $body       = '';

    public ?int   $replyingTo     = null;
    public string $replyBody      = '';
    public string $replyGuestName = '';
    public string $replyingToName = '';

    // Só controla se mostra o alerta de sucesso — não esconde o formulário
    public bool $submitted = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    protected function rules(): array
    {
        return [
            'guestName'  => Auth::check() ? ['nullable'] : ['required', 'string', 'max:100'],
            'guestEmail' => ['nullable', 'email', 'max:150'],
            'body'       => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    protected function replyRules(): array
    {
        return [
            'replyGuestName' => Auth::check() ? ['nullable'] : ['required', 'string', 'max:100'],
            'replyBody'      => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    protected $messages = [
        'guestName.required'      => 'Por favor, informe seu nome.',
        'body.required'           => 'O comentário não pode estar vazio.',
        'body.min'                => 'O comentário deve ter pelo menos 3 caracteres.',
        'replyGuestName.required' => 'Por favor, informe seu nome.',
        'replyBody.required'      => 'A resposta não pode estar vazia.',
    ];

    public function submit(): void
    {
        if (! $this->post->comment) return;

        $this->validate($this->rules());

        Comment::create([
            'post_id'     => $this->post->id,
            'user_id'     => Auth::id(),
            'parent_id'   => null,
            'guest_name'  => Auth::check() ? null : $this->guestName,
            'guest_email' => Auth::check() ? null : $this->guestEmail,
            'body'        => $this->body,
            'status'      => 'pending',
            'ip_address'  => request()->ip(),
        ]);

        // Limpa só o campo de texto, mantém nome/email preenchidos
        $this->reset(['body']);
        $this->submitted = true;
    }

    public function startReply(int $commentId, string $authorName): void
    {
        $this->replyingTo     = $commentId;
        $this->replyingToName = $authorName;
        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->resetErrorBag();
    }

    public function cancelReply(): void
    {
        $this->replyingTo     = null;
        $this->replyingToName = '';
        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->resetErrorBag();
    }

    public function submitReply(): void
    {
        if (! $this->replyingTo || ! $this->post->comment) return;

        $this->validate($this->replyRules());

        $parent = Comment::find($this->replyingTo);

        // Mantém máximo 2 níveis — reply de reply vai pro mesmo pai
        $parentId = $parent?->parent_id ?? $this->replyingTo;

        Comment::create([
            'post_id'    => $this->post->id,
            'user_id'    => Auth::id(),
            'parent_id'  => $parentId,
            'guest_name' => Auth::check() ? null : $this->replyGuestName,
            'body'       => $this->replyBody,
            'status'     => 'pending',
            'ip_address' => request()->ip(),
        ]);

        $this->cancelReply();
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.post-comments', [
            'comments' => $this->post->comments()
                ->with(['replies.user'])
                ->get(),
        ]);
    }
}