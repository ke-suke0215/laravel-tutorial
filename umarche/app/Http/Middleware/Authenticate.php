<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    // どのURLでログインしようとしているのかわかりやすいようにプロパティを作成
    // Providers/RouteServiceProvider.php において as で設定した名前を使用する
    protected $user_route = 'user.login';
    protected $owner_route = 'owner.login';
    protected $admin_route = 'admin.login';

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 認証されていないときにユーザーがリダイレクトされるパスを取得します。
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Route::is でURLが引数と合致しているか真偽値で返してくれる
            if(Route::is('owner.*')) {
                return route($this->owner_route);
            } elseif(Route::is('admin.*')) {
                return route($this->admin_route);
            } else {
                return route($this->user_route);
            }
        }
    }
}
