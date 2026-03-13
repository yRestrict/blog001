{{--
    PARTIAL: quill-modals.blade.php
    Inclua com @include('dashboard.partials.quill-modals') antes do @endsection('content')
    O modal de imagem foi removido — agora o resize é feito direto no editor
    via quill-image-resize-module (arrastar bordas + toolbar de alinhamento).
--}}

{{-- ── Modal: Inserir Vídeo YouTube ──────────────────────────────────────────── --}}
<div class="modal fade" id="quill-video-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="background:#1e1e2e;">
                <h5 class="modal-title text-white">
                    <i class="fa fa-youtube-play mr-2"></i> Inserir Vídeo YouTube
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small">Link do YouTube</label>
                    <input type="text" id="yt-url-input" class="form-control"
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-bold small">Largura</label>
                            <input type="text" id="yt-width-input" class="form-control" value="100%">
                            <small class="text-muted">px ou %</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="font-weight-bold small">Altura</label>
                            <input type="text" id="yt-height-input" class="form-control" value="400px">
                            <small class="text-muted">px</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" id="yt-apply-btn" class="btn btn-danger btn-sm">
                    <i class="fa fa-youtube-play"></i> Inserir vídeo
                </button>
            </div>

        </div>
    </div>
</div>