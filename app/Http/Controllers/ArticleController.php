<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Attachment;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;



class ArticleController extends Controller
{
    public function __construct()
    {
        // アクションに合わせたpolicyのメソッドで認可されていないユーザーはエラーを投げる
        $this->authorizeResource(Article::class, 'article');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::with('attachments')->latest()->Paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article = new Article($request->all());
        $article->user_id = $request->user()->id;
        $paths = [];

        DB::beginTransaction();
        try {
            $article->save();

            if ($files = $request->file('file')) {
                foreach ($files as $file) {
                    $file_name = $file->getClientOriginalName();
                    $path = Storage::putFile('articles', $file);
                    $attachment = new Attachment();
                    $attachment->article_id = $article->id;
                    $attachment->org_name = $file_name;
                    $attachment->name = basename($path);
                    $attachment->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            if ($paths) {
                foreach ($paths as $path) {
                    Storage::delete($path);
                }
            }
            DB::rollback();
            return back()
                ->withErrors($e->getMessage());
        }

        return redirect()
            ->route('articles.index')
            ->with(['flash_message' => '登録が完了しました']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {

        // バリデーション
        $request->validate([
            'caption' => 'required|max:255',
            'info' => 'max:255'
        ]);

        // Articleのデータを更新
        $article->fill($request->all());

        // トランザクション開始
        DB::beginTransaction();
        try {
            // Article保存
            $article->save();

            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            back()->withErrors(['error' => '保存に失敗しました']);
        }

        return redirect(route('articles.index'))->with(['flash_message' => '更新が完了しました']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $attachments = $article->attachments;
        $article->delete();

        foreach ($attachments as $attachment) {
            Storage::delete('articles/' . $attachment->name);
        }
        return redirect()
            ->route('articles.index')
            ->with(['flash_message' => '削除しました']);
    }
}
