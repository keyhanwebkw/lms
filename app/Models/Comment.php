<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Manager;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Traits\Pagination;

class Comment extends Model
{
    use HasFactory;
    use Pagination;

    public const COMMENTABLE_TYPES = [
        'Article',
        'Course',
    ];

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'userID',
        'managerID',
        'content',
        'hasReply',
        'parentID',
        'status',
    ];

    protected $casts = [
        'ID' => 'integer',
        'commentable_type' => 'string',
        'commentable_id' => 'integer',
        'userID' => 'integer',
        'managerID' => 'integer',
        'content' => 'string',
        'hasReply' => 'boolean',
        'parentID' => 'integer',
        'status' => 'string',
        'created' => 'integer',
        'updated' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
    }

    public static function cacheTag($key='')
    {
        return 'comments' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'comments' . $key;
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parentID')
            ->with(
                'replies',
                'authorUser:ID,name,family',
                'authorManager:id,name,family',
                'authorUser.storage'
            );
    }

    public function authorManager()
    {
        return $this->belongsTo(Manager::class, 'managerID');
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
