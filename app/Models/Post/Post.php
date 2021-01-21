<?php

namespace App\Models\Post;

use App\Models\Auth\User;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Post
 * @property int id
 * @property string title
 * @property string content
 * @property int user_id
 * @property DateTime published
 * @property DateTime updated_at
 * @property User user
 * @package App\Models\Post
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'published',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
