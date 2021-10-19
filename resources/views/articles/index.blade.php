@extends('layouts.app')
@section('title', '一覧画面')
@section('content')
<h1>画像一覧</h1>
<section class="row" data-masonry='{ "percentPosition": true }'>
    @foreach ($articles as $article)
    <div class="col-6 col-md-4 col-lg-3 col-sl-2 mb-4">
        <article class="card position-relative">
            <img src="{{ $article->image_url }}" class="card-img-top">
            <div class="card-title mx-3">
                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none stretched-link">
                    {{ $article->caption }}
                </a>
            </div>
        </article>
    </div>
    @endforeach
</section>
@endsection