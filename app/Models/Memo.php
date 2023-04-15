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
}
