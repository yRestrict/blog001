@extends("frontend.master")

@section("title", ($settings->site_title ?? 'Blog') . " - " . ($settings->site_description ?? 'Slogan'))

@section("content")

    @include("frontend.home.inc.featuredpost")

    <section class="section-feature-1">
        <div class="container-fluid">
            <div class="row">
                @include("frontend.home.inc.recentpost")
                {{-- @include("frontend.home.inc.sidebar") --}}
            </div>
        </div>
    </section>

@endsection