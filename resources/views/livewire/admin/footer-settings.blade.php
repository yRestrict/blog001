<div>
    <h5 class="mb-4">Categorias Populares</h5>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="font-14 font-weight-600 mb-2">Ordenar categorias por</label>
                <p class="text-muted font-12 mb-3">
                    Define como as categorias são exibidas no rodapé do site.
                </p>

                <div class="footer-order-options">

                    <label wire:key="order-posts" class="footer-option-card {{ $footer_category_order === 'posts' ? 'active' : '' }}">
                        <input type="radio"
                            wire:model.live="footer_category_order"
                            value="posts"
                            class="d-none">
                        <div class="footer-option-icon">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <div>
                            <div class="footer-option-title">Mais posts</div>
                            <div class="footer-option-desc">Exibe as categorias que têm mais postagens publicadas.</div>
                        </div>
                        <div class="footer-option-check">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </label>

                    <label wire:key="order-views" class="footer-option-card {{ $footer_category_order === 'views' ? 'active' : '' }}">
                        <input type="radio"
                            wire:model.live="footer_category_order"
                            value="views"
                            class="d-none">
                        <div class="footer-option-icon">
                            <i class="fa fa-eye"></i>
                        </div>
                        <div>
                            <div class="footer-option-title">Mais visualizações</div>
                            <div class="footer-option-desc">Exibe as categorias que receberam mais visitas.</div>
                        </div>
                        <div class="footer-option-check">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </label>

                </div>

                @error('footer_category_order')
                    <span class="text-danger font-12 mt-2 d-block">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="btn btn-primary">
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm mr-1"></span>
                </span>
                <i class="fa fa-save" wire:loading.remove wire:target="save"></i>
                Salvar configurações
            </button>
        </div>
    </div>
</div>

