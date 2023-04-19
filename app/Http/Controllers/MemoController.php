<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\Image;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$memos = Memo::where('user_id','=',\Auth::id())->where('del_flg','=',0)->orderBy('id','DESC')
        //->get();

        $search = $request->search;

        $query = Memo::search($search);

        $memos = $query->select('*')->orderBy('id','DESC')->paginate(10);

        for ($i = 0; $i < count($memos); $i++)
        {
            // 更新日時の表記の成形
            $replace_dates[$i] = str_replace('-','/',$memos[$i]['updated_at']);
            
            // 更新日時の年月日のみに成形
            $dates[$i] = substr($replace_dates[$i],0,10);
            
            $images[$i] = Memo::find($memos[$i]['id'])->Images()->get();
        }

        return view('memos.index',compact('memos','dates','images'));
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
        $date = date("Y/m/d");
        $replace_dete = str_replace('/','',$date);
        
        $dir = 'image_'.$replace_dete;

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

        for($image_i = 0; $image_i < count($request->file('new_image')); $image_i++)
        {
            // 画像ファイルの名前を取得
            $image_names[$image_i] = $request->file('new_image')[$image_i]->getClientOriginalName();

            // 画像ファイルのアップロード
            $request->file('new_image')[$image_i]->storeAs('public/'.$dir,$image_names[$image_i]);

            // アップロードした画像ファイルのパスを取得
            $file_paths[$image_i] = 'storage/'.$dir.'/'. $image_names[$image_i];

            // imageテーブルへインサート
            $image = Image::create([
                'name' => $image_names[$image_i],
                'path' => $file_paths[$image_i],
                'user_id' => \Auth::id(),
                'del_flg' => 0,
            ]);

            $image_id = $image->id;

            $memo->Images()->attach(['image_id' => $image_id]);
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
        $tags = Memo::find($id)->Tags()->get();
        $images = Memo::find($id)->Images()->get();

        return view('memos.show',compact('memo','tags','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $memo = Memo::find($id);
        $tags = Memo::find($id)->Tags()->get();

        return view('memos.edit',compact('memo','tags'));
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
