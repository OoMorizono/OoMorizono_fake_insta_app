@extends('layouts.app')
@section('title', '詳細画面')
@section('content')
<h1>画像詳細</h1>
<section class="mb-3">
    <article class="card shadow position-relative">
        <figure class="m-3">
            <div class="row">
                <div class="col-6">
                    <img src="{{ $article->image_url }}" width="100%">
                </div>
                <div class="col-6">
                    <figcaption>
                        <h1>
                            {{ $article->caption }}
                        </h1>
                        <h3>
                            {{ $article->info }}
                        </h3>
                    </figcaption>
                </div>
            </div>
        </figure>
        <a href="{{ route('articles.edit', $article) }}">
            <i class="fas fa-edit position-absolute top-0 end-0 fs-1"></i>
        </a>
    </article>
</section>
<form action="{{ route('articles.destroy', $article) }}" method="post" id="form">
    @csrf
    @method('delete')
</form>
<div class="d-grid col-6 mx-auto gap-3">
    <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg">戻る</a>
    <input type="submit" value="削除" form="form" class="btn btn-danger btn-lg"
        onclick="if (!confirm('本当に削除してよろしいですか？')) {return false};">
</div>
@endsection