<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Keyhanweb\Subsystem\Models\Model;
use Keyhanweb\Subsystem\Models\Storage;

class Answer extends Model
{
    protected $table = 'answers';
    protected $fillable = [
        'questionID',
        'answer',
        'contentSID',
        'correct',
        'managerID',
        'archived',
    ];
    protected $casts = [
        'questionID' => 'integer',
        'answer' => 'string',
        'contentSID' => 'string',
        'correct' => 'boolean',
        'managerID' => 'integer',
        'archived' => 'integer',
    ];

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'contentSID');
    }

    protected static function booted()
    {
        static::saved(fn() => self::clearCache());
    }

    protected static function clearCache(): void
    {
        Cache::tags(self::cacheTag())->flush();
        Question::clearCache();

    }

    public static function cacheTag($key='')
    {
        return 'answers' . $key;
    }

    public static function keyCache($key = '')
    {
        return 'answers' . $key;
    }
}
