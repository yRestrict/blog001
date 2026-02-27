<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;



class PostComments extends Component
{
    public Post $post;

    // ─── Formulário principal ─────────────────────────────────────────────────
    public string  $guestName  = '';
    public string  $guestEmail = '';
    public string  $body       = '';

    // ─── Reply ───────────────────────────────────────────────────────────────
    public ?int    $replyingTo     = null; // id do comentário pai
    public string  $replyBody      = '';
    public string  $replyGuestName = '';

    // ─── Feedback ────────────────────────────────────────────────────────────
    public bool    $submitted = false;

    protected function rules(): array
    {
        return [
            'guestName'  => ['required', 'string', 'max:100'],
            'guestEmail' => ['nullable', 'email', 'max:150'],
            'body'       => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    protected function replyRules(): array
    {
        return [
            'replyGuestName' => ['required', 'string', 'max:100'],
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

    // ─── Enviar comentário principal ──────────────────────────────────────────
    public function submit(): void
    {
        // Bloqueia se comentários estão desativados no post
        if (! $this->post->comment) {
            return;
        }

        $user = Auth::user();

        $this->validate($this->rules());

        Comment::create([
            'post_id'     => $this->post->id,
            'user_id'     => Auth::id(), // null se não logado
            'parent_id'   => null,
            'guest_name'  => Auth::check() ? null : $this->guestName,
            'guest_email' => Auth::check() ? null : $this->guestEmail,
            'body'        => $this->body,
            'status'      => 'pending',
            'ip_address'  => request()->ip(),
        ]);

        $this->reset(['guestName', 'guestEmail', 'body']);
        $this->submitted = true;
    }

    // ─── Abrir/fechar formulário de reply ────────────────────────────────────
    public function startReply(int $commentId): void
    {
        $this->replyingTo     = $commentId;
        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->resetErrorBag();
    }

    public function cancelReply(): void
    {
        $this->replyingTo     = null;
        $this->replyBody      = '';
        $this->replyGuestName = '';
        $this->resetErrorBag();
    }

    // ─── Enviar reply ─────────────────────────────────────────────────────────
    public function submitReply(): void
    {
        if (! $this->replyingTo || ! $this->post->comment) {
            return;
        }

        $this->validate($this->replyRules());

        Comment::create([
            'post_id'    => $this->post->id,
            'user_id'    => Auth::id(),
            'parent_id'  => $this->replyingTo,
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
            'comments' => $this->post->comments()->with('replies')->get(),
        ]);
    }
}