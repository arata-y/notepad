<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memos = Memo::where('user_id','=',\Auth::id())->where('del_flg','=',0)->orderBy('id','DESC')
                 ->get();
        
        return view('memos.index',compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*1.tagsテーブルからデータを取得
          2.ビューにtagsテーブルから取得したデータを渡す
        */

        $tags = Tag::where('user_id','=',\Auth::id())->where('del_flg','=',0)->orderBy('id','DESC')
                ->get();

        return view('memos.create',compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*1.メモテーブルへのインサート
          2.新規タグ(new_tag)がtagsテーブルにあるか確認する
            ->新規タグは複数(5個)同時に作成ができるため、配列に格納する
            　そのため、繰り返しでtagsテーブルに存在するか確認する
          3.新規タグだった場合はtagsテーブルにインサート,memo_tagテーブルにインサート
          4.既存のタグが選択された場合は、memo_tagsテーブルにインサートする
        */

        // メモテーブルへインサート
           $memo = Memo::create([
               'name' => $request->name,
               'content' => $request->content,
               'user_id' => \Auth::id(),
               'del_flg' => 0,
           ]);

        // 既存のタグを取得(配列)
        $tag_exists = Tag::where('user_id', '=',\Auth::id())->whereIn('name',$request->new_tag)
        ->exists();

        // 新規タグの入力があった場合
        // tagsテーブルに存在しなかった場合
        if (!empty($request->new_tag[0]) && !$tag_exists)
        {
            // tag_idを配列で取得
            for($i = 0; $i < count($request->new_tag); $i++)
            {
                // タグテーブルのインサート
                $tag = Tag::create([
                'name' => $request->new_tag[$i],
                'user_id' => \Auth::id(),
                'del_flg' => 0,
                ]);

                // タグidの取得
                $tag_id = $tag->id;

                // memoテーブルとtagテーブルの紐づけを追加
                $memo->Tags()->attach(['tag_id' => $tag_id]);
            }
        }

        // 既存タグの入力があった場合
        if (!empty($request->tags[0]))
        {
            for($j = 0; $j < count($request->tags); $j++)
            {
                // タグidの取得
                $tag_id = $request->tags[$j];

                // memoテーブルとtagテーブルの紐づけを追加
                $memo->Tags()->attach(['tag_id' => $tag_id]);
            }
        }

        return to_route('memos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $memo = Memo::find($id);
        return view('memos.show',compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
