<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Memoモデルをインポート
use App\Models\Memo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function create()
    {
        // ログインしているユーザーの情報を渡す
        $user = \Auth::user();
        return view('create', compact('user'));
    }

    // create 画面からPOSTのリクエストが送られてきたときの処理
    public function store(Request $request)
    {
        $data = $request->all();
        // ddで中のデータを出力(dump die)（デバッグとして使用）
        // データを出力した後にプログラムを終了させる。
        // dd($data);
        // POSTされたデータをDBのmemoテーブルに追加
        // MemoモデルにDBへ保存する命令を出す
        $memo_id = Memo::insertGetId(['content' => $data['content'], 'user_id' => $data['user_id'], 'status' => 1]);

        // リダイレクトの処理
        return redirect()->route(('home'));
    }
}
