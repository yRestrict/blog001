@extends("frontend.master")
@section("pageTitle", isset($pageTitle) ? $pageTitle : "Posts")
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection

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
