<?php

namespace App\Models;

use App\Traits\ConvertDateTimeToTimezone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use SoftDeletes, HasTags, ConvertDateTimeToTimezone;

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /** @var string[] */
    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'status' => 'integer',
    ];


    /**
     * item Method.
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * comments Method.
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
