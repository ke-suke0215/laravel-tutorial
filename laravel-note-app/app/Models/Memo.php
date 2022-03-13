<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    public function myMemo($user_id) {
        $tag = \Request::query('tag');
        // タグがなければその人が持っているメモを全て取得
        if (empty($tag)) {
            return $this::select('memos.*')->where('user_id', $user_id)->where('status', 1)->get();
        } else {
            $memos = $this::select('memos.*')
                ->leftJoin('memo_tags', 'memo_id', '=', 'memos.id')
                ->leftJoin('tags', 'tags.id', '=', 'memo_tags.tag_id')
                ->where('tags.name', $tag)
                ->where('tags.user_id', $user_id)
                ->where('memos.user_id', $user_id)
                ->where('status', 1)
                ->get();
            return $memos;
        }
    }
}
