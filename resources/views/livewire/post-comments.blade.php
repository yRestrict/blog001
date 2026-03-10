<div>
    <div class="post-single-comments">
        @if (! $post->comment)
            <div class="alert alert-secondary">
                <i class="las la-comment-slash"></i> Os comentários estão desativados neste post.
            </div>
        @else
            @if ($submitted)
                <div class="alert alert-success contact_msg rounded-0 mb-4">
                    <i class="las la-check-circle"></i>
                    Seu comentário foi enviado e aguarda aprovação. Obrigado!
                </div>
            @endif
            @if ($comments->count() > 0)
                <h4><i class="fa fa-comments"></i> {{ $comments->count() }} {{ $comments->count() === 1 ? 'Comentário' : 'Comentários' }}</h4>
    
                <ul class="comments">
                    @foreach ($comments as $comment)
                        <li class="comment-item {{ $loop->first ? 'pt-0' : '' }}" id="comment-{{ $comment->id }}">

                            <div class="d-flex align-items-start">     
                            <div class="mr-3">
                                @if ($comment->user && $comment->user->profile)
                                    <img src="{{ asset('uploads/author/' . $comment->user->profile) }}" style="width:42px;height:42px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width:42px;height:42px;font-size:16px;">
                                        {{ strtoupper(substr($comment->author_name,0,1)) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">

                                    @if ($comment->user?->username)
                                        <a href="{{ route('frontend.user', $comment->user->username) }}" class="author-link">
                                            <span class="comment-author-name">{{ $comment->author_name }}</span>
                                        </a>
                                    @else
                                        <span class="comment-author-name">{{ $comment->author_name }}</span>
                                    @endif

                                    @if ($comment->user_id === $post->author_id)
                                        <span class="badge bg-success text-white" style="font-size:11px;">Autor</span>
                                    @endif
                                </div>

                                <div class="comment-time">
                                    <span class="text-muted">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="comment-body">
                            <p>
                                {{ $comment->body }}
                            </p>

                            @if ($replyingTo !== $comment->id)
                                <a href="#"
                                wire:click.prevent="startReply({{ $comment->id }}, '{{ addslashes($comment->author_name) }}')"
                                class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-reply"></i> Responder
                                </a>
                            @endif

                            @if ($replyingTo === $comment->id)
                                @include('livewire.frontend.reply-form')
                            @endif

                        </div>

                            @if ($comment->replies->isNotEmpty())

                                <ul class="comment-replies">

                                    @foreach ($comment->replies as $reply)

                                        <li class="reply-item d-flex" id="comment-{{ $reply->id }}">
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

                                            <div style="flex:1; margin-left:6px;">

                                                <div class="d-flex align-items-center flex-wrap" style="gap:6px;">

                                                    @if ($reply->user?->username)
                                                        <a href="{{ route('frontend.user', $reply->user->username) }}" class="author-link">
                                                            <span class="comment-author-name">{{ $reply->author_name }}</span>
                                                        </a>
                                                    @else
                                                        <span class="comment-author-name">{{ $reply->author_name }}</span>
                                                    @endif

                                                    @if ($reply->user_id && $reply->user_id === $post->author_id)
                                                        <span class="badge bg-success text-white" style="font-size:10px;">Autor</span>
                                                    @endif
                                                    <span class="reply-badge">
                                                        Resposta
                                                    </span>
                                                    <small class="text-muted">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </small>

                                                </div>

                                                <p class="comment-text">
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
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <input type="text" name="name" id="name"
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
                                    <input type="email" name="email" id="email"
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
                                <textarea wire:model.defer="body" id="message" cols="30" rows="5"
                                          class="form-control @error('body') is-invalid @enderror"
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