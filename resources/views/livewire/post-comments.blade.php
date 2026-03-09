<div>
    <div class="post-single-comments">

        {{-- Comentários desativados --}}
        @if (! $post->comment)
            <div class="alert alert-secondary">
                <i class="las la-comment-slash"></i> Os comentários estão desativados neste post.
            </div>
        @else

            {{-- mensagem de sucesso --}}
            @if ($submitted)
                <div class="alert alert-success contact_msg rounded-0 mb-4">
                    <i class="las la-check-circle"></i>
                    Seu comentário foi enviado e aguarda aprovação. Obrigado!
                </div>
            @endif

            {{-- Lista de comentários --}}
            @if ($comments->count() > 0)

                <h4>{{ $comments->count() }} {{ $comments->count() === 1 ? 'Comentário' : 'Comentários' }}</h4>

                <ul class="comments">

                    @foreach ($comments as $comment)

                        <li class="comment-item {{ $loop->first ? 'pt-0' : '' }}" id="comment-{{ $comment->id }}">

                            <div class="d-flex align-items-start">     
                            <div class="mr-3">
                                @if ($comment->user && $comment->user->profile)
                                    <img src="{{ asset('uploads/author/' . $comment->user->profile) }}"
                                        class="rounded-circle"
                                        style="width:42px;height:42px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;font-size:16px;">
                                        {{ strtoupper(substr($comment->author_name,0,1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Nome + hora --}}
                            <div>
                                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">

                                    @if ($comment->user?->username)
                                        <a href="{{ route('frontend.user', $comment->user->username) }}">
                                            <strong>{{ $comment->author_name }}</strong>
                                        </a>
                                    @else
                                        <strong>{{ $comment->author_name }}</strong>
                                    @endif

                                    @if ($comment->user_id === $post->author_id)
                                        <span class="badge bg-success text-white" style="font-size:11px;">Autor</span>
                                    @endif
                                </div>

                                <div style="font-size:13px;">
                                    <span class="text-muted">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- Conteúdo alinhado na borda --}}
                        <div style="margin-top:8px;">

                            <p style="margin-bottom:8px;">
                                {{ $comment->body }}
                            </p>

                            @if ($replyingTo !== $comment->id)
                                <a href="#"
                                wire:click.prevent="startReply({{ $comment->id }}, '{{ addslashes($comment->author_name) }}')"
                                class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-reply"></i> Responder
                                </a>
                            @endif

                        </div>

                            {{-- Replies --}}
                            @if ($comment->replies->isNotEmpty())

                                <ul class="mt-3 pl-4 border-left"
                                    style="list-style:none;border-width:3px;border-color:#007bff;padding-left:18px;">

                                    @foreach ($comment->replies as $reply)

                                        <li class="mb-3 d-flex"
    id="comment-{{ $reply->id }}"
    style="border-bottom:1px solid rgba(0,0,0,0.08); padding:12px 0 12px 14px;">

                                            {{-- Avatar reply --}}
                                            <div class="shrink-0 mr-3">

                                                @if ($reply->user && $reply->user->profile)
                                                    <img src="{{ asset('uploads/author/' . $reply->user->profile) }}"
                                                         alt="{{ $reply->author_name }}"
                                                         class="rounded-circle"
                                                         style="width:32px;height:32px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                         style="width:32px;height:32px;font-size:13px;">
                                                        {{ strtoupper(substr($reply->author_name,0,1)) }}
                                                    </div>
                                                @endif

                                            </div>

                                            {{-- conteúdo reply --}}
                                            <div style="flex:1; margin-left:6px;">

                                                <div class="d-flex align-items-center flex-wrap" style="gap:6px;">

                                                    @if ($reply->user?->username)
                                                        <a href="{{ route('frontend.user', $reply->user->username) }}">
                                                            <strong>{{ $reply->author_name }}</strong>
                                                        </a>
                                                    @else
                                                        <strong>{{ $reply->author_name }}</strong>
                                                    @endif

                                                    @if ($reply->user_id && $reply->user_id === $post->author_id)
                                                        <span class="badge bg-success text-white" style="font-size:10px;">Autor</span>
                                                    @endif

                                                    <span style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;background:rgba(0,123,255,0.1);color:#007bff;">
                                                        Resposta
                                                    </span>

                                                    {{-- hora ao lado --}}
                                                    <small class="text-muted">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </small>

                                                </div>

                                                <p class="mb-1 mt-1">
                                                    {{ $reply->body }}
                                                </p>

                                                @if ($replyingTo !== $reply->id)
                                                    <a href="#"
                                                       wire:click.prevent="startReply({{ $reply->id }}, '{{ addslashes($reply->author_name) }}')"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-reply"></i> Responder
                                                    </a>
                                                @endif

                                                @if ($replyingTo === $reply->id)
                                                    @include('livewire.frontend.reply-form')
                                                @endif

                                            </div>

                                        </li>

                                    @endforeach

                                </ul>

                            @endif

                        </li>

                    @endforeach

                </ul>

            @else

                <p class="text-muted">Seja o primeiro a comentar!</p>

            @endif


            {{-- Formulário principal --}}
            <div id="comment-form-location">

                <div class="comments-form" id="comment-form">

                    <h4>Deixe um comentário</h4>

                    @auth
                        <p>Comentando como <strong>{{ auth()->user()->name }}</strong></p>
                    @else
                        <p>Seu email não será publicado. Campos obrigatórios marcados com *.</p>
                    @endauth

                    <div class="row">

                        @guest
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text"
                                           wire:model.defer="guestName"
                                           class="form-control @error('guestName') is-invalid @enderror"
                                           placeholder="Nome *">

                                    @error('guestName')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email"
                                           wire:model.defer="guestEmail"
                                           class="form-control @error('guestEmail') is-invalid @enderror"
                                           placeholder="Email (opcional)">

                                    @error('guestEmail')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endguest


                        <div class="col-md-12">

                            <div class="form-group">
                                <textarea wire:model.defer="body"
                                          class="form-control @error('body') is-invalid @enderror"
                                          rows="5"
                                          placeholder="Mensagem *"></textarea>

                                @error('body')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>


                        <div class="col-lg-12">
                            <button wire:click="submit()" class="btn-custom">
                                Enviar
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>
</div>