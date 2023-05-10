<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'del_flg',
    ];

    /**
     * The Memos that belong to the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Memos(): BelongsToMany
    {
        return $this->belongsToMany(Memo::class);
    }

    public function scopeWhereAriveTag($query)
    {
        return $query->where('del_flg', '=', 0);
    }

    public function scopeSearch($query,$search)
    {
        if ($search !== null)
        {
            $search_convert = mb_convert_kana($search,'s');

            $search_split = preg_split('/[\s]+/',$search_convert);

            foreach($search_split as $value)
            {
                $query->where('name','like','%'.$value.'%');
            }
        }
        return $query;
    }

    public function scopeSearchTagId($query,$tags)
    {
        for($ti = 0; $ti < count($tags); $ti++)
        {
            $tag[$ti] = Tag::find($tags[$ti]['id'])->Memos()->get();

            for($tj = 0; $tj < count($tag[$ti]); $tj++)
            {
                $tag_id[$tj] = $tag[$ti][$tj]['id'];
            }

        }

        return $tag_id;
    }
}
