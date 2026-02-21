<div>
    @if(Session::get('info'))
        <div class="alert alert-info alert-dismissible fade show form-alert" role="alert" role="alert">
            <div class="alert-content">
                <div class="alert-text">
                    {!! Session::get('info') !!}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>               
            </button>
        </div>
    @endif

    @if(Session::get('fail'))
        <div class="alert alert-danger alert-dismissible fade show form-alert" role="alert" role="alert">
            <div class="alert-content">
                <div class="alert-text">
                    {!! Session::get('fail') !!}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>               
            </button>
        </div>
    @endif

    @if(Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show form-alert" role="alert" role="alert">
            <div class="alert-content">
                <div class="alert-text">
                    {!! Session::get('success') !!}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>               
            </button>
        </div>
    @endif
</div>