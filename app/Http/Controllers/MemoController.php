<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\Image;

class MemoController extends Controller
{
    private $memoinstance;
    private $taginstance;

    public function __construct()
    {
        $this->memoinstance = new Memo();
        $this->taginstance = new Tag();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 検索バーに入力された文字を取得
        $search = $request->search;

        $type = $request->type;

        if (!empty($type))
        {
            // typeがタイトルかタグか判定する
            if ($type === '1')
            {
                // メモ一覧からタイトルにてあいまい検索を実施
                $query = $this->memoinstance->search($search);

                $memos = $query->select('*')->whereAriveMemo()->orderBy('updated_at','DESC')->paginate(10);
            }
            else
            {
                // メモ一覧からタグにてあいまい検索を実施
                $query = $this->taginstance->search($search);

                $tags = $query->select('*')->get();

                $tag_id = $this->taginstance->SearchTagId($tags);
                
                $memos = $this->memoinstance->select('*')->whereIn('id',$tag_id)->orderBy('updated_at')->paginate(10);
            }
        }
        else
        {
            // メモの一覧を取得
            $memos = $this->memoinstance->select('*')->whereAriveMemo()->orderBy('updated_at','DESC')->paginate(10);
        }
        
        for ($i = 0; $i < count($memos); $i++)
        {
            $tags[$i] = $this->memoinstance->find($memos[$i]['id'])->Tags()->get();

            // 更新日時の表記の成形
            $replace_dates[$i] = str_replace('-','/',$memos[$i]['updated_at']);
            
            // 更新日時の年月日のみに成形
            $dates[$i] = substr($replace_dates[$i],0,10);
            
            $images[$i] = $this->memoinstance->find($memos[$i]['id'])->Images()->get();
        }

        return view('memos.index',compact('memos','dates','images','tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 既存のタグを取得
        $tags = $this->taginstance->where('user_id','=',\Auth::id())->whereAriveTag()->orderBy('id','DESC')
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
        // 日時を取得
        $date = date("Y/m/d");

        $replace_dete = str_replace('/','',$date);
        
        // 画像ファイルを格納するディレクトリ
        $dir = 'image_'.$replace_dete;

        $user_id = \Auth::id();

        // メモテーブルへインサート
        $memo = $this->memoinstance->insertMemo($request->name,$request->content,$user_id,0);

        // 既存のタグを取得
        $tag_exists = $this->taginstance->where('user_id', '=',\Auth::id())->whereIn('name',$request->new_tag)
        ->exists();

        // 新規タグの入力がされた場合かつ、tagsテーブルに存在しない場合
        if (!empty($request->new_tag[0]) && !$tag_exists)
        {
            // tag_idを配列で取得
            for($i = 0; $i < count($request->new_tag); $i++)
            {
                // タグテーブルのインサート
                $tag = $this->taginstance->create([
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
        $memo = $this->memoinstance->find($id);

        $tags = $this->memoinstance->find($id)->Tags()->get();

        $images = $this->memoinstance->find($id)->Images()->get();

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
        $memo = $this->memoinstance->find($id);

        $memo_tags = $this->memoinstance->find($id)->Tags()->get();
        
        $tags = $this->taginstance->where('user_id','=',\Auth::id())->whereAriveTag()->orderBy('id','DESC')
                ->get();

        $images = $this->memoinstance->find($id)->Images()->get();

        return view('memos.edit',compact('memo','tags','images','memo_tags'));
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
        // 日時を取得
        $date = date("Y/m/d");
        $replace_dete = str_replace('/','',$date);
        
        // 画像ファイルを格納するディレクトリ名
        $dir = 'image_'.$replace_dete;

        $memo = $this->memoinstance->find($id);
        
        $memo->name = $request->name;

        $memo->content = $request->content;

        $memo->save();

        // メモとタグの紐づけを解除
        $memo->Tags()->detach();

        // チェックボックスで既存のタグが選択された場合
        if (isset($request->tags))
        {
            foreach($request->tags as $tag)
            {
                // memoテーブルとtagテーブルの紐づけを追加
                $memo->Tags()->attach(['tag_id' => $tag]);

                $memo->touch();
            }
        }

        // 既存のタグを取得(配列)
        $tag_exists = $this->taginstance->where('user_id', '=',\Auth::id())->whereIn('name',$request->new_tag)
        ->exists();

        // 新規タグの入力がされた場合かつ、tagsテーブルに存在しない場合
        if (!empty($request->new_tag[0]) && !$tag_exists)
        {
            // tag_idを配列で取得
            for($tag_i = 0; $tag_i < count($request->new_tag); $tag_i++)
            {
                // タグテーブルのインサート
                $tag = $this->taginstance->create([
                'name' => $request->new_tag[$tag_i],
                'user_id' => \Auth::id(),
                'del_flg' => 0,
                ]);

                // タグidの取得
                $tag_id = $tag->id;

                // memoテーブルとtagテーブルの紐づけを追加
                $memo->Tags()->attach(['tag_id' => $tag_id]);

                $memo->touch();
            }
        }

        // メモと画像の紐づけを解除
        $memo->Images()->detach();

        // チェックボックスで既存の画像が選択された場合
        if (isset($request->images))
        {
            foreach($request->images as $image)
            {
                // memoテーブルとimageテーブルの紐づけを追加
                $memo->Images()->attach(['image_id' => $image]);

                $memo->touch();
            }
        }

        // 新規画像の入力された場合
        if (!empty($request->file('new_image')[0]))
        {
            // tag_idを配列で取得
            for($image_i = 0; $image_i < count($request->file('new_image')); $image_i++)
            {
                // 画像ファイルの名前を取得
                $image_names[$image_i] = $request->file('new_image')[$image_i]->getClientOriginalName();

                // 画像ファイルのアップロード
                $request->file('new_image')[$image_i]->storeAs('public/'.$dir,$image_names[$image_i]);

                // アップロードした画像ファイルのパスを取得
                $file_paths[$image_i] = 'storage/'.$dir.'/'. $image_names[$image_i];

                // 画像テーブルのインサート
                $image = Image::create([
                'name' => $image_names[$image_i],
                'path' => $file_paths[$image_i],
                'user_id' => \Auth::id(),
                'del_flg' => 0,
                ]);

                // タグidの取得
                $image_id = $image->id;

                // memoテーブルとtagテーブルの紐づけを追加
                $memo->Images()->attach(['image_id' => $image_id]);

                $memo->touch();
            }
        }

        return  to_route('memos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $memo = $this->memoinstance->find($id);

        $memo->del_flg = 1;

        $memo->save();

        return  to_route('memos.index');
    }
}
