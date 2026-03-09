<div class="comment-reply-form mt-3">
    <h6><i class="las la-reply"></i> Respondendo a <strong>{{ $replyingToName }}</strong></h6>
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

        <div class="col-lg-12">
            <button wire:click="submitReply()" class="btn-custom mr-2">
                <i class="las la-paper-plane"></i> Enviar
            </button>
            <a href="#" wire:click.prevent="cancelReply()" class="btn-reply">
                Cancelar
            </a>
        </div>
    </div>
</div>