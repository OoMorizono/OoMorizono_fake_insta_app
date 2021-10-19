@extends('layouts.app')
@section('title', '一覧画面')
@section('content')
<h1>画像一覧</h1>
<section class="row" data-masonry='{ "percentPosition": true }'>
    @foreach ($articles as $article)
    <div class="col-6 col-md-4 col-lg-3 col-sl-2 mb-4">
        <article class="card">
            <img src="{{ $article->image_url }}" class="card-img-top">
            <div class="card-title mx-3">
                {{ $article->caption }}
            </div>
        </article>
    </div>
    @endforeach
</section>
@endsection