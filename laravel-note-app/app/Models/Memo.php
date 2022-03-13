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
                ->leftJoin('tags', 'tags.id', '=', 'memos.tag_id')
                ->where('tags.id', $tag)
                ->where('tags.user_id', $user_id)
                ->where('memos.user_id', $user_id)
                ->where('status', 1)
                ->get();
            return $memos;
        }
    }
}
