<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Memo
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MemoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Memo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Memo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Memo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Memo extends Model
{
    use HasFactory;

    public const DOING = "doing";
    public const DONE = "done";
    public const PENDING = "pending";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'author_id',
        'title',
        'body',
        'status',
        'is_public',
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'memo_id');
    }
}
