<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


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
        $articles = Article::all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image',
            'caption' => 'required|max:255',
            'info' => 'max:255'
        ]);

        // Articleのデータを用意
        $article = new Article();
        $article->fill($request->all());

        // ユーザーIDを追加
        $article->user_id = $request->user()->id;

        // ファイルの用意
        $file = $request->file;
        $name = $file->getClientOriginalName();

        // トランザクション開始
        DB::beginTransaction();
        try {
            // Article保存
            $article->save();
            // 画像ファイル保存
            $path = Storage::putFile('articles', $file);
            // Attachmentモデルの情報を用意
            $attachment = new Attachment([
                'article_id' => $article->id,
                'org_name' => basename($file),
                'name' => basename($path)
            ]);
            // Attachment保存
            $attachment->save();
            // トランザクション終了(成功)
            DB::commit();
        } catch (\Exception $e) {
            // トランザクション終了(失敗)
            DB::rollback();
            back()->withErrors(['error' => '保存に失敗しました']);
        }

        return redirect(route('articles.index'))->with(['flash_message' => '登録が完了しました']);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
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
        DB::beginTransaction();
        try {
            // 削除処理
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->getMessage());
        }
        return redirect()
            ->route('articles.index')
            ->with(['flash_message' => '削除しました']);
    }
}
