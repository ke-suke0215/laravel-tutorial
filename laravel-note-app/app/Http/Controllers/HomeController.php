<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// モデルをインポート
use App\Models\Memo;
use App\Models\Tag;

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
        // 自分のメモ一覧を取得
        $user = \Auth::user();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        return view('create', compact('user', 'memos'));
    }

    public function create()
    {
        // ログインしているユーザーの情報を渡す
        $user = \Auth::user();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        return view('create', compact('user', 'memos'));
    }

    // create 画面からPOSTのリクエストが送られてきたときの処理
    public function store(Request $request)
    {
        $data = $request->all();
        // ddで中のデータを出力(dump die)（デバッグとして使用）
        // データを出力した後にプログラムを終了させる。
        // dd($data);

        // 同じ人が同一のタグを入力したか調べる
        $exist_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])->first();
        // dd($exist_tag);

        if (empty($exist_tag['id'])) {
            // 先にタグをインサート
            // insertGetIdの戻り値はインサートに成功した場合は自動生成されたidになる
            $tag_id = Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]);
        } else {
            // 同一のタグがもともと存在する場合
            $tag_id = $exist_tag['id'];
        }

        // POSTされたデータをDBのmemoテーブルに追加
        // MemoモデルにDBへ保存する命令を出す
        $memo_id = Memo::insertGetId([
            'content' => $data['content'], 
            'user_id' => $data['user_id'], 
            'tag_id' => $tag_id,
            'status' => 1
        ]);

        // リダイレクトの処理
        return redirect()->route(('home'));
    }
    
    // ルーティングでURLパラメータとして渡したidを引数にする
    public function edit($id)
    {
        $user = \Auth::user();
        // 引数で受け取ったidと一致する + ログイン中のユーザーのメモである + 消去されていないメモである + 取ってくるデータは1つ
        $memo = Memo::where('id', $id)->where('user_id', $user['id'])->where('status', 1)->first();
        // メモ一覧を取得
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        // タグ一覧を取得
        $tags = Tag::where('user_id', $user['id'])->get();
        return view('edit', compact('memo', 'user', 'memos', 'tags'));
    }
    
    // 引数はフォームから受け取った値とルーティングのURLパラメータ
    public function update(Request $request, $id)
    {
        // $requestのままだと形式がよくわからない感じ
        $inputs = $request->all();
        // dd($inputs);
        Memo::where('id', $id)->update(['content' => $inputs['content'], 'tag_id' =>$inputs['tag_id']]);
        return redirect()->route(('home'));
    }

    // 削除機能
    public function delete(Request $request, $id)
    {
        Memo::where('id', $id)->update(['status' => 2]);
        return redirect()->route(('home'))->with('success', 'メモの削除が完了しました。');
    }
}
