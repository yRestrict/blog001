@extends("frontend.master")

@section("title", ($settings->site_title ?? 'Blog') . " - " . ($settings->site_description ?? 'Slogan'))

@section("content")


<section class="post-single post-single-layout-2">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12">
                @include("frontend.post.inc.post-content")
            </div>
        </div>
    </div>
</section>
@endsection
