<div class="comment-reply-form mt-3">
    <h6><i class="las la-reply"></i> Respondendo a <strong>{{ $replyAuthorName }}</strong></h6>
    <div class="row">

        @guest
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text"
                           wire:model.defer="replyGuestName"
                           class="form-control @error('replyGuestName') is-invalid @enderror"
                           placeholder="Seu nome *">
                    @error('replyGuestName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endguest

        <div class="col-md-12">
            <div class="form-group">
                <textarea wire:model.defer="replyBody"
                          class="form-control @error('replyBody') is-invalid @enderror"
                          rows="3"
                          placeholder="Escreva sua resposta..."></textarea>
                @error('replyBody')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if ($replySubmitted)
            <div class="col-md-12">
                <div class="alert alert-success rounded-0 mt-2 mb-3">
                    <i class="las la-check-circle"></i>
                    {{--
                        Auto-aprovado se:
                        - Owner (sempre)
                        - Author (sempre — comentários de equipe são auto-aprovados em qualquer post)
                        Aguarda aprovação se:
                        - Guest
                        - Usuário logado sem role de owner/author
                    --}}
                    @auth
                        @if (auth()->user()->isOwner() || auth()->user()->isAuthor())
                            Resposta publicada com sucesso!
                        @else
                            Sua resposta foi enviada e aguarda aprovação. Obrigado!
                        @endif
                    @else
                        Sua resposta foi enviada e aguarda aprovação. Obrigado!
                    @endauth
                </div>
            </div>
        @endif

        <div class="col-lg-12">
            <button wire:click="submitReply()" class="btn btn-sm btn-primary mr-2">
                <i class="las la-paper-plane"></i> Enviar
            </button>
            <a href="#" wire:click.prevent="cancelReply()" class="btn btn-sm btn-outline-secondary">
                Cancelar
            </a>
        </div>

    </div>
</div>