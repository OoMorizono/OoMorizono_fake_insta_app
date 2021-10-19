<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Laravel\Socialite\Facades\Socialite;
    use App\Models\User;
    use App\Models\IdentityProvider;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use App\Providers\RouteServiceProvider;

    class OAuthController extends Controller
    {
        public function redirectToProvider()
        {
            return Socialite::driver('github')->redirect();
        }

        public function oauthCallback()
        {
            // 認証情報が返ってこなかった場合はログイン画面にリダイレクト
            try {
                $socialUser = Socialite::with('github')->user();
            } catch (\Exception $e) {
                return redirect('/login')->withErrors(['oauth_error' => '予期せぬエラーが発生しました']);
            }

            // emailで検索してユーザーが見つかればそのユーザーを、見つからなければ新しいインスタンスを生成
            $user = User::firstOrNew(['email' => $socialUser->getEmail()]);

            // ユーザーが認証済みか確認
            if (!$user->exists) {
                $user->name = $socialUser->getNickname() ?? $socialUser->name;
                $identityProvider = new IdentityProvider([
                    'id' => $socialUser->getId(),
                    'name' => 'github'
                ]);

                DB::beginTransaction();
                try {
                    $user->save();
                    $user->identityProvider()->save($identityProvider);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()
                        ->route('login')
                        ->withErrors(['transaction_error' => '保存に失敗しました']);
                }
            }

            // ログイン
            Auth::login($user);

            return redirect()->route(RouteServiceProvider::HOME);
        }
    }
