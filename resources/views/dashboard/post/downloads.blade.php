@extends('dashboard.master')
@section('pageTitle', 'Downloads — ' . $post->title)
 
@section('content')
    <livewire:admin.post-downloads :post="$post" />
@endsection