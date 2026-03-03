@extends("frontend.master")

@section("title", $user->name." - ".config('app.sitesettings')::first()->site_title)
@section("content")
@include("frontend.user.inc.author")
<section class="blog-author mt-30">
    <div class="container-fluid">
        <div class="row">
            @include("frontend.user.inc.post")
            {{-- @if (visible_sidebar()) --}}
                @include("frontend.user.inc.sidebar")
            {{-- @endif --}}
        </div>
    </div>
</section>
@endsection
