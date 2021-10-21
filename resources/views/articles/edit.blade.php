@extends('layouts.main')
@section('title', '編集画面')
@section('content')
@include('partial.errors')
<section>
    <article class="card shadow">
        <figure class="m-3">
            <div class="row">
                <div class="col-6">
                    <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">
                        @foreach ($article->attachments as $attachment)
                        <article class="w-full px-4 md:w-1/4 text-xl text-gray-800 leading-normal">
                            <img class="w-full mb-2" src="{{ Storage::url('articles/' . $attachment->name) }}"
                                alt="image">
                        </article>
                        @endforeach
                    </div>
                </div>
                <div class="col-6">
                    <figcaption>

                        <form action="{{ route('articles.update', $article) }}" method="post" id="form">
                            @csrf
                            @method('patch')
                            <div class="mb-3">
                                <label for="caption" class="form-label">イメージの説明を入力してください</label>
                                <input type="text" name="caption" id="caption" class="form-control"
                                    value="{{ old('caption',$article->caption) }}">
                            </div>
                            <div>
                                <label for="info" class="form-label">その他情報を入力してください</label>
                                <textarea name="info" id="info" rows="5"
                                    class="form-control">{{ old('info', $article->info) }}</textarea>
                            </div>
                        </form>
                    </figcaption>
                </div>
            </div>
        </figure>
    </article>

    <div class="d-grid gap-3 col-6 mx-auto">
        <input type="submit" value="更新" form="form" class="btn btn-success btn-lg">
        <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg">戻る</a>
    </div>
</section>
@endsection