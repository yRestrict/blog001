<div>

    {{-- ── Aviso de comentários desativados ───────────────────────────────────── --}}
    @if (! $post->comment)
        <div class="alert alert-secondary mt-4">
            <i class="fa fa-comment-slash"></i> Os comentários estão desativados neste post.
        </div>
        @return
    @endif

    {{-- ── Confirmação de envio ────────────────────────────────────────────────── --}}
    @if ($submitted)
        <div class="alert alert-success mt-4">
            <i class="fa fa-check-circle"></i>
            Seu comentário foi enviado e está aguardando aprovação. Obrigado!
        </div>
    @endif

    {{-- ── Lista de comentários aprovados ──────────────────────────────────────── --}}
    <div class="mt-4" id="comentarios">
        <h5 class="font-weight-bold mb-3">
            <i class="fa fa-comments"></i>
            {{ $comments->count() }} {{ Str::plural('Comentário', $comments->count()) }}
        </h5>

        @forelse ($comments as $comment)
            <div class="card card-box mb-3" id="comment-{{ $comment->id }}">
                <div class="card-body">

                    {{-- Cabeçalho do comentário --}}
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3"
                             style="width:40px; height:40px; font-size:16px; flex-shrink:0;">
                            {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $comment->author_name }}</strong>
                            <small class="text-muted d-block">
                                {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    {{-- Corpo --}}
                    <p class="mb-2">{{ $comment->body }}</p>

                    {{-- Botão de reply --}}
                    @if ($replyingTo !== $comment->id)
                        <button wire:click="startReply({{ $comment->id }})"
                                class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-reply"></i> Responder
                        </button>
                    @endif

                    {{-- ── Formulário de reply ──────────────────────────────── --}}
                    @if ($replyingTo === $comment->id)
                        <div class="mt-3 pl-3 border-left border-primary">
                            <h6 class="text-primary">Respondendo a {{ $comment->author_name }}</h6>

                            <div class="form-group">
                                <input type="text"
                                       wire:model.defer="replyGuestName"
                                       class="form-control form-control-sm @error('replyGuestName') is-invalid @enderror"
                                       placeholder="Seu nome *">
                                @error('replyGuestName')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <textarea wire:model.defer="replyBody"
                                          class="form-control form-control-sm @error('replyBody') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Escreva sua resposta..."></textarea>
                                @error('replyBody')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button wire:click="submitReply()" class="btn btn-sm btn-primary">
                                <i class="fa fa-paper-plane"></i> Enviar Resposta
                            </button>
                            <button wire:click="cancelReply()" class="btn btn-sm btn-secondary ml-1">
                                Cancelar
                            </button>
                        </div>
                    @endif

                    {{-- ── Replies aprovados ─────────────────────────────────── --}}
                    @if ($comment->replies->isNotEmpty())
                        <div class="mt-3 pl-4 border-left border-light">
                            @foreach ($comment->replies as $reply)
                                <div class="d-flex mb-3" id="comment-{{ $reply->id }}">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                         style="width:32px; height:32px; font-size:13px; flex-shrink:0;">
                                        {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $reply->author_name }}</strong>
                                        <small class="text-muted ml-2">{{ $reply->created_at->diffForHumans() }}</small>
                                        <p class="mb-0 mt-1">{{ $reply->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        @empty
            <p class="text-muted">Seja o primeiro a comentar!</p>
        @endforelse
    </div>

    {{-- ── Formulário de novo comentário ───────────────────────────────────────── --}}
    @if (! $submitted)
        <div class="card card-box mt-4">
            <div class="card-header font-weight-bold">
                <i class="fa fa-pen"></i> Deixe seu Comentário
            </div>
            <div class="card-body">

                {{-- Se logado, exibe o nome automaticamente --}}
                @auth
                    <p class="text-muted small mb-3">
                        Comentando como <strong>{{ auth()->user()->name }}</strong>
                    </p>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>Nome</b> <span class="text-danger">*</span></label>
                                <input type="text"
                                       wire:model.defer="guestName"
                                       class="form-control @error('guestName') is-invalid @enderror"
                                       placeholder="Seu nome">
                                @error('guestName')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>E-mail</b> <small class="text-muted">(opcional)</small></label>
                                <input type="email"
                                       wire:model.defer="guestEmail"
                                       class="form-control @error('guestEmail') is-invalid @enderror"
                                       placeholder="seu@email.com">
                                @error('guestEmail')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="form-group">
                    <label><b>Comentário</b> <span class="text-danger">*</span></label>
                    <textarea wire:model.defer="body"
                              class="form-control @error('body') is-invalid @enderror"
                              rows="5"
                              placeholder="Escreva seu comentário aqui..."></textarea>
                    @error('body')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button wire:click="submit()" class="btn btn-primary">
                    <i class="fa fa-paper-plane"></i> Enviar Comentário
                </button>

                <small class="text-muted d-block mt-2">
                    <i class="fa fa-info-circle"></i> Seu comentário será publicado após aprovação.
                </small>
            </div>
        </div>
    @endif

</div>