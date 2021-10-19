<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('詳細画面') }}
        </h2>
    </x-slot>
    <div class="container">
        <section class="mb-3">
            <article class="card shadow position-relative">
                <figure class="m-3">
                    {{-- 省略 --}}
                </figure>
                @can('update', $article)
                <a href="{{ route('articles.edit', $article) }}">
                    <i class="fas fa-edit position-absolute top-0 end-0 fs-1"></i>
                </a>
                @endcan
            </article>
        </section>
        @can('destroy', $article)
        <form action="{{ route('articles.destroy', $article) }}" method="post" id="form">
            @csrf
            @method('delete')
        </form>
        <div class="d-grid col-6 mx-auto gap-3">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg">戻る</a>
            <input type="submit" value="削除" form="form" class="btn btn-danger btn-lg"
                onclick="if (!confirm('本当に削除してよろしいですか？')) {return false};">
        </div>
        @endcan
    </div>
</x-app-layout>