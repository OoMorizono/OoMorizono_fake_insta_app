@extends('layouts.main')
@section('title', '一覧画面')
@section('content')

<section class="row position-relative" data-masonry='{ "percentPosition": true }'>
    @foreach ($articles as $article)
    <div class="mb-4">
        <div class="card">
        @auth
        <div class="h3">{{ Auth::user()->name }}</div>
        @else
        <div>guest</div>
        @endauth
        </div>
        <article class="card position-relative">
            <img src="{{ $article->image_url }}" class="card-img-top">
            <div class="card-title mx-3 h4">
                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none stretched-link">
                    {{ $article->caption }}
                </a>
            </div>
        </article>
    </div>
    @endforeach
</section>
<a href="{{ route('articles.create') }}" class="position-fixed fs-1 bottom-0 end-0" style="color:#ff00d4;">
    <i class="fas fa-plus-circle"></i>
</a>
@endsection