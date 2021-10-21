@extends('layouts.main')
@section('title', '詳細画面')
@section('content')
@include('partial.flash')
@include('partial.errors')
<section>
    <article class="card shadow position-relative">
        <figure class="m-3">
            <div class="row">
                <div class="col-6">
                    <img src="{{ $article->image_url }}" width="100%">
                </div>
                <div class="col-6">
                    <figcaption>
                        <h1> {{ $article->caption }} </h1>
                        <h3> {{ $article->info }} </h3>
                    </figcaption>
                </div>
            </div>
        </figure>
        @can('update', $article)
        <a href="{{ route('articles.edit', $article) }}">
            <i class="fas fa-edit position-absolute top-0 end-0 fs-1"></i>
        </a>
        @endcan
    </article>
    <div class="d-grid gap-3 col-6 mx-auto">
        @can('delete', $article)
        <form action="{{ route('articles.destroy', $article) }}" method="post" id="form">
            @csrf
            @method('DELETE')
        </form>
        <input form="form" type="submit" value="削除" onclick="if(!confirm('削除していいですか')){return false}"
            class="btn btn-danger btn-lg">
        @endcan
        <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg">戻る</a>
    </div>
</section>
@endsection