<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Memo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'content',
        'user_id',
        'del_flg',
        'name',
    ];

    /**
     * The Tags that belong to the Memo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * The Images that belong to the Memo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class);
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

    public function scopeWhereAriveMemo($query)
    {
        return $query->where('del_flg', '=', 0);
    }
}
