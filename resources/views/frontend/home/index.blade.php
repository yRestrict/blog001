@extends("frontend.master")

@section("title", ($settings->site_title ?? 'Blog') . " - " . ($settings->site_description ?? 'Slogan'))

@section("content")

<livewire:frontend.featured-slider />

<section class="section-feature-1">
    <div class="container-fluid">
        <div class="row">
            @include("frontend.home.inc.recentpost")
            <div class="col-lg-4 oredoo-sidebar">
                <div class="theiaStickySidebar">
                    @include('components.sidebar.index')
                </div>
            </div>

        </div>
    </div>
</section>

@endsection